<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\CardAssignment;
use App\Models\TimeLog;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DesignerController extends Controller
{
    /**
     * Show designer dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get all design tasks assigned to this user
        $myTasks = CardAssignment::with(['card.board.project', 'card.subtasks'])
            ->where('user_id', $user->id)
            ->whereHas('card', function($query) {
                $query->whereIn('status', ['todo', 'in_progress', 'review']);
            })
            ->orderByRaw("FIELD(assignment_status, 'in_progress', 'assigned', 'completed')")
            ->orderBy('created_at', 'desc')
            ->get();

        // Get current working task
        $currentTask = $myTasks->where('assignment_status', 'in_progress')->first() 
                    ?? $myTasks->where('assignment_status', 'assigned')->first();

        // Get active time log
        $activeTimeLog = TimeLog::where('user_id', $user->id)
            ->whereNull('end_time')
            ->with('card')
            ->first();

        // Get user's projects
        $myProjects = Project::whereHas('members', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['boards.cards'])
            ->get();

        // Get completed designs (for portfolio)
        $completedDesigns = Card::whereHas('assignedMembers', function($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->where('status', 'done')
            ->with(['board.project'])
            ->latest('updated_at')
            ->take(12)
            ->get();

        // Get time logs
        $myTimeLogs = TimeLog::where('user_id', $user->id)
            ->whereNotNull('end_time')
            ->with(['card.board.project'])
            ->latest()
            ->get();

        // Statistics
        $completedTasks = CardAssignment::where('user_id', $user->id)
            ->where('assignment_status', 'completed')
            ->count();

        $totalHoursWorked = TimeLog::where('user_id', $user->id)
            ->whereNotNull('duration_minutes')
            ->sum('duration_minutes') / 60;

        return view('designer.dashboard', compact(
            'myTasks',
            'currentTask',
            'activeTimeLog',
            'myProjects',
            'completedDesigns',
            'myTimeLogs',
            'completedTasks',
            'totalHoursWorked'
        ));
    }

    /**
     * Start working on a design task
     */
    public function startTask(Card $card)
    {
        $user = Auth::user();

        DB::beginTransaction();
        try {
            // Check if user is assigned to this card
            $assignment = CardAssignment::where('card_id', $card->id)
                ->where('user_id', $user->id)
                ->first();

            if (!$assignment) {
                return back()->with('error', 'You are not assigned to this design task!');
            }

            // Check if user is already working on another task
            $existingWork = TimeLog::where('user_id', $user->id)
                ->whereNull('end_time')
                ->first();

            if ($existingWork) {
                return back()->with('error', 'Please pause your current design task first!');
            }

            // Update user status to working
            $user->update(['current_task_status' => 'working']);

            // Update assignment status
            $assignment->update([
                'assignment_status' => 'in_progress',
                'started_at' => $assignment->started_at ?? now(),
            ]);

            // Update card status if it's still todo
            if ($card->status === 'todo') {
                $card->update(['status' => 'in_progress']);
            }

            // Create time log
            TimeLog::create([
                'card_id' => $card->id,
                'user_id' => $user->id,
                'start_time' => now(),
                'description' => 'Designing: ' . $card->card_title,
            ]);

            DB::commit();

            return back()->with('success', 'Started working on design task!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to start task: ' . $e->getMessage());
        }
    }

    /**
     * Pause current design work
     */
    public function pauseTask()
    {
        $user = Auth::user();

        DB::beginTransaction();
        try {
            // Find active time log
            $activeTimeLog = TimeLog::where('user_id', $user->id)
                ->whereNull('end_time')
                ->first();

            if (!$activeTimeLog) {
                return back()->with('error', 'No active design task to pause!');
            }

            // End time log
            $activeTimeLog->update(['end_time' => now()]);

            // Update user status to idle
            $user->update(['current_task_status' => 'idle']);

            // Update card actual hours
            $activeTimeLog->card->updateActualHours();

            DB::commit();

            return back()->with('success', 'Design task paused successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to pause task: ' . $e->getMessage());
        }
    }

    /**
     * Complete current design task
     */
    public function completeTask(Card $card)
    {
        $user = Auth::user();

        DB::beginTransaction();
        try {
            // Find assignment
            $assignment = CardAssignment::where('card_id', $card->id)
                ->where('user_id', $user->id)
                ->first();

            if (!$assignment) {
                return back()->with('error', 'You are not assigned to this design task!');
            }

            // End active time log if exists
            $activeTimeLog = TimeLog::where('user_id', $user->id)
                ->where('card_id', $card->id)
                ->whereNull('end_time')
                ->first();

            if ($activeTimeLog) {
                $activeTimeLog->update(['end_time' => now()]);
                $card->updateActualHours();
            }

            // Update user status to idle
            $user->update(['current_task_status' => 'idle']);

            // Update assignment as completed
            $assignment->update([
                'assignment_status' => 'completed',
                'completed_at' => now(),
            ]);

            // Check if all assignments are completed, then mark card as done
            $allCompleted = $card->assignments()
                ->where('assignment_status', '!=', 'completed')
                ->count() === 0;

            if ($allCompleted) {
                $card->update(['status' => 'done']);
            }

            DB::commit();

            return redirect()
                ->route('designer.dashboard')
                ->with('success', 'Design completed! Great work! 🎨');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to complete task: ' . $e->getMessage());
        }
    }

    /**
     * View my design portfolio
     */
    public function portfolio()
    {
        $user = Auth::user();

        $completedDesigns = Card::whereHas('assignedMembers', function($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->where('status', 'done')
            ->with(['board.project', 'assignedMembers'])
            ->latest('updated_at')
            ->paginate(20);

        return view('designer.portfolio', compact('completedDesigns'));
    }

    /**
     * View my time logs
     */
    public function timeLogs()
    {
        $user = Auth::user();

        $timeLogs = TimeLog::where('user_id', $user->id)
            ->with(['card.board.project', 'subtask'])
            ->latest()
            ->paginate(20);

        // Statistics
        $today = TimeLog::where('user_id', $user->id)
            ->whereDate('start_time', today())
            ->sum('duration_minutes') / 60;

        $thisWeek = TimeLog::where('user_id', $user->id)
            ->whereBetween('start_time', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('duration_minutes') / 60;

        $thisMonth = TimeLog::where('user_id', $user->id)
            ->whereMonth('start_time', now()->month)
            ->sum('duration_minutes') / 60;

        return view('designer.time-logs', compact('timeLogs', 'today', 'thisWeek', 'thisMonth'));
    }

    /**
     * View my statistics
     */
    public function statistics()
    {
        $user = Auth::user();

        // Tasks statistics
        $totalTasks = CardAssignment::where('user_id', $user->id)->count();
        $completedTasks = CardAssignment::where('user_id', $user->id)
            ->where('assignment_status', 'completed')
            ->count();
        $inProgressTasks = CardAssignment::where('user_id', $user->id)
            ->where('assignment_status', 'in_progress')
            ->count();

        // Time statistics
        $totalHours = TimeLog::where('user_id', $user->id)
            ->whereNotNull('duration_minutes')
            ->sum('duration_minutes') / 60;

        // Projects
        $activeProjects = Project::whereHas('members', function($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->count();

        // Weekly chart data
        $weeklyHours = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $hours = TimeLog::where('user_id', $user->id)
                ->whereDate('start_time', $date)
                ->sum('duration_minutes') / 60;
            
            $weeklyHours[] = [
                'date' => $date->format('D'),
                'hours' => round($hours, 1)
            ];
        }

        return view('designer.statistics', compact(
            'totalTasks',
            'completedTasks',
            'inProgressTasks',
            'totalHours',
            'activeProjects',
            'weeklyHours'
        ));
    }
}