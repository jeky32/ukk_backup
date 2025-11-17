<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Board;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['board', 'assignedUser'])
            ->orderBy('created_at', 'desc')
            ->paginate(Auth::user()->getSettings()->items_per_page ?? 25);

        return view('admin.tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'board_id' => 'required|exists:boards,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:todo,in_progress,review,done',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
        ]);

        $task = Task::create($validated);

        // Log activity
        Activity::create([
            'user_id' => Auth::id(),
            'task_id' => $task->id,
            'project_id' => $task->board->project_id ?? null,
            'type' => 'task_created',
            'description' => Auth::user()->full_name . ' created task "' . $task->title . '"',
        ]);

        return redirect()->back()->with('success', 'Task created successfully!');
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'sometimes|in:low,medium,high,urgent',
            'status' => 'sometimes|in:todo,in_progress,review,done',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);

        // Log activity
        Activity::create([
            'user_id' => Auth::id(),
            'task_id' => $task->id,
            'project_id' => $task->board->project_id ?? null,
            'type' => 'task_updated',
            'description' => Auth::user()->full_name . ' updated task "' . $task->title . '"',
        ]);

        return redirect()->back()->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        // Log activity before deletion
        Activity::create([
            'user_id' => Auth::id(),
            'project_id' => $task->board->project_id ?? null,
            'type' => 'task_deleted',
            'description' => Auth::user()->full_name . ' deleted task "' . $task->title . '"',
        ]);

        $task->delete();

        return redirect()->back()->with('success', 'Task deleted successfully!');
    }
}
