<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Intern;
use Illuminate\Http\Request;

class TaskController extends Controller
{
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
     * Update a task
     */
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'priority' => 'sometimes|in:Low,Medium,High',
            'status' => 'sometimes|in:Not Started,In Progress,Completed,On Hold',
            'due_date' => 'sometimes|date',
        ]);

        $task->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully',
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
        $this->authorize('delete', $task);

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully'
        ]);
    }

    /**
     * Get all tasks for an intern
     */
    public function getInternTasks(Intern $intern)
    {
        $tasks = $intern->tasks()
            ->orderBy('due_date', 'asc')
            ->get();

        return response()->json([
            'tasks' => $tasks
        ]);
    }
}
