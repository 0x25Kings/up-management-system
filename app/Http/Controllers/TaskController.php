<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Intern;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Get a specific task by ID
     */
    public function show(Task $task)
    {
        $task->load('intern');
        return response()->json([
            'success' => true,
            'task' => $task
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
            'priority' => 'required|in:Low,Medium,High',
            'due_date' => 'required|date',
        ]);

        $validated['assigned_by'] = auth()->id();
        $validated['status'] = 'Not Started';

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
            'priority' => 'sometimes|in:Low,Medium,High',
            'status' => 'sometimes|in:Not Started,In Progress,Completed,On Hold',
            'due_date' => 'sometimes|date',
            'progress' => 'sometimes|integer|min:0|max:100',
            'notes' => 'nullable|string',
            'documents.*' => 'nullable|file|max:10240', // Max 10MB per file
        ]);

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
