<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Intern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    private function parseChecklistFromText(?string $text): ?array
    {
        if ($text === null) {
            return null;
        }

        $lines = preg_split("/\r\n|\r|\n/", $text) ?: [];
        $items = [];

        foreach ($lines as $line) {
            $label = trim($line);
            if ($label === '') {
                continue;
            }
            $items[] = ['label' => $label, 'done' => false];
        }

        return count($items) > 0 ? $items : null;
    }

    /**
     * Get a specific task by ID
     */
    public function show(Task $task)
    {
        $task->load('intern.schoolRelation');

        // Get all group members if this is a group task
        $groupMembers = [];
        if ($task->group_id) {
            $groupTasks = Task::where('group_id', $task->group_id)
                ->with('intern.schoolRelation')
                ->get();

            $groupMembers = $groupTasks->map(function($t) {
                return [
                    'id' => $t->intern->id,
                    'name' => $t->intern->name,
                    'email' => $t->intern->email,
                    'school' => $t->intern->schoolRelation->name ?? $t->intern->school
                ];
            })->toArray();
        }

        return response()->json([
            'success' => true,
            'task' => $task,
            'group_members' => $groupMembers
        ]);
    }

    /**
     * Store a new task assignment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'intern_id' => 'required|exists:interns,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'checklist_text' => 'nullable|string|max:5000',
            'priority' => 'required|in:Low,Medium,High',
            'due_date' => 'required|date',
            'group_id' => 'nullable|string',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $validated['assigned_by'] = $user ? $user->id : null;
        $validated['status'] = 'Not Started';

        $checklist = $this->parseChecklistFromText($validated['checklist_text'] ?? null);
        unset($validated['checklist_text']);
        if ($checklist !== null) {
            $validated['checklist'] = $checklist;
        }

        $task = Task::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Task assigned successfully',
            'task' => $task
        ]);
    }

    /**
     * Update a task (including progress, notes, and document uploads)
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'checklist_text' => 'nullable|string|max:5000',
            'priority' => 'sometimes|in:Low,Medium,High',
            'status' => 'sometimes|in:Not Started,In Progress,Completed,On Hold',
            'due_date' => 'sometimes|date',
            'progress' => 'sometimes|integer|min:0|max:100',
            'notes' => 'nullable|string',
            'documents.*' => 'nullable|file|max:10240', // Max 10MB per file
        ]);

        if (array_key_exists('checklist_text', $validated)) {
            $checklist = $this->parseChecklistFromText($validated['checklist_text']);
            unset($validated['checklist_text']);
            $validated['checklist'] = $checklist;
        }

        // Handle document uploads
        if ($request->hasFile('documents')) {
            $uploadedDocs = [];
            $path = 'tasks/documents';

            foreach ($request->file('documents') as $file) {
                $filename = $file->getClientOriginalName();
                $file->storeAs($path, $filename, 'public');
                $uploadedDocs[] = "{$path}/{$filename}";
            }

            // If task already has documents, merge them
            $existingDocs = $task->documents ?? [];
            $validated['documents'] = array_merge($existingDocs, $uploadedDocs);
        }

        // If status is being changed to 'In Progress', record the start time
        if ($request->has('status') && $request->status === 'In Progress' && $task->status !== 'In Progress') {
            $validated['started_at'] = now('Asia/Manila');
        }

        // Admin-only true completion: setting status to Completed records completed_date
        if ($request->has('status') && $request->status === 'Completed') {
            $validated['progress'] = 100;
            if ($task->completed_date === null) {
                $validated['completed_date'] = now('Asia/Manila');
            }
        }

        $task->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully with progress and documents',
            'task' => $task
        ]);
    }

    /**
     * Mark a task as completed
     */
    public function complete(Task $task)
    {
        $task->update([
            'status' => 'Completed',
            'completed_date' => now('Asia/Manila')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task marked as completed'
        ]);
    }

    /**
     * Delete a task
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully'
        ]);
    }

    /**
     * Auto-update task progress based on attendance records
     */
    public function autoUpdateInternTasks(Intern $intern)
    {
        $intern->autoUpdateTaskProgress();

        return response()->json([
            'success' => true,
            'message' => 'Task progress updated automatically based on attendance'
        ]);
    }

    /**
     * Get all tasks for an intern (with auto-update)
     */
    public function getInternTasks(Intern $intern)
    {
        // Auto-update progress based on attendance
        $intern->autoUpdateTaskProgress();

        $tasks = $intern->tasks()
            ->orderBy('due_date', 'asc')
            ->get();

        return response()->json([
            'tasks' => $tasks
        ]);
    }
}
