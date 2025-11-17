<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\CardAssignment;
use App\Models\Comment;
use App\Models\TimeLog;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeveloperController extends Controller
{
    /**
     * Show developer dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Get all tasks assigned to this user
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

        // === TAMBAHAN KODE BARU ===

        // DAFTAR TUGAS SAYA: Cards dengan status 'todo' yang di-assign ke user ini
        $todoTasks = CardAssignment::with(['card.board.project', 'card.comments'])
            ->where('user_id', $user->id)
            ->whereHas('card', function($query) {
                $query->where('status', 'todo');
            })
            ->get()
            ->sortBy([
                function($assignment) {
                    $priorityOrder = ['high' => 1, 'medium' => 2, 'low' => 3];
                    return $priorityOrder[$assignment->card->priority] ?? 4;
                },
                function($assignment) {
                    return $assignment->card->due_date ?? '9999-12-31';
                }
            ])
            ->values();

        // TUGAS SAAT INI: Card tertinggi dari todoTasks atau yang sedang dikerjakan
        $currentTaskDetail = null;
        $currentTaskComments = collect([]);

        if ($activeTimeLog && $activeTimeLog->card) {
            $currentTaskDetail = CardAssignment::with(['card.board.project', 'card.comments.user', 'card.creator'])
                ->where('user_id', $user->id)
                ->where('card_id', $activeTimeLog->card_id)
                ->first();

            if ($currentTaskDetail) {
                $currentTaskComments = $currentTaskDetail->card->comments()
                    ->with('user')
                    ->latest()
                    ->limit(3)
                    ->get();
            }
        } elseif ($todoTasks->isNotEmpty()) {
            $currentTaskDetail = $todoTasks->first();
            $currentTaskComments = $currentTaskDetail->card->comments()
                ->with('user')
                ->latest()
                ->limit(3)
                ->get();
        }

        // TIME TRACKING: Time logs di hari terakhir ada data
        $latestTimeLog = TimeLog::where('user_id', $user->id)
            ->whereNotNull('end_time')
            ->latest('end_time')
            ->first();

        $todayTimeLogs = collect([]);
        $todayTotalHours = 0;

        if ($latestTimeLog) {
            $latestDate = \Carbon\Carbon::parse($latestTimeLog->end_time)->format('Y-m-d');

            $todayTimeLogs = TimeLog::where('user_id', $user->id)
                ->whereNotNull('end_time')
                ->whereDate('end_time', $latestDate)
                ->with(['card'])
                ->orderBy('start_time')
                ->get();

            $todayTotalHours = $todayTimeLogs->sum('duration_minutes') / 60;
        }

        // UPDATE TERBARU: Comments dengan keyword tertentu
        $importantUpdates = Comment::with(['card', 'user'])
            ->where(function($query) {
                $query->where('comment_text', 'like', '%[APPROVED]%')
                    ->orWhere('comment_text', 'like', '%[REJECTED]%')
                    ->orWhere('comment_text', 'like', '%[REVIEW]%')
                    ->orWhere('comment_text', 'like', '%[BLOCKER]%');
            })
            ->whereHas('card', function($query) use ($user) {
                $query->whereHas('assignments', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->latest()
            ->limit(10)
            ->get();

        // PRODUCTIVITY: Statistics untuk bulan ini
        $thisMonth = now()->month;
        $thisYear = now()->year;

        $completedThisMonth = CardAssignment::where('user_id', $user->id)
            ->where('assignment_status', 'completed')
            ->whereYear('completed_at', $thisYear)
            ->whereMonth('completed_at', $thisMonth)
            ->count();

        $completedCardIds = CardAssignment::where('user_id', $user->id)
            ->where('assignment_status', 'completed')
            ->whereYear('completed_at', $thisYear)
            ->whereMonth('completed_at', $thisMonth)
            ->pluck('card_id');

        $averageTimePerTask = 0;
        if ($completedCardIds->isNotEmpty()) {
            $totalMinutes = TimeLog::where('user_id', $user->id)
                ->whereIn('card_id', $completedCardIds)
                ->whereNotNull('duration_minutes')
                ->sum('duration_minutes');

            $averageTimePerTask = $completedCardIds->count() > 0
                ? $totalMinutes / $completedCardIds->count() / 60
                : 0;
        }

        $completedWithDeadline = CardAssignment::where('user_id', $user->id)
            ->where('assignment_status', 'completed')
            ->whereYear('completed_at', $thisYear)
            ->whereMonth('completed_at', $thisMonth)
            ->whereHas('card', function($query) {
                $query->whereNotNull('due_date');
            })
            ->with('card')
            ->get();

        $onTimeCount = 0;
        foreach ($completedWithDeadline as $assignment) {
            if ($assignment->completed_at && $assignment->card->due_date) {
                $completedDate = \Carbon\Carbon::parse($assignment->completed_at);
                $dueDate = \Carbon\Carbon::parse($assignment->card->due_date);
                if ($completedDate->lte($dueDate)) {
                    $onTimeCount++;
                }
            }
        }

        $onTimeRate = $completedWithDeadline->count() > 0
            ? ($onTimeCount / $completedWithDeadline->count()) * 100
            : 0;

        $productivityRating = 0;
        if ($completedThisMonth > 0) {
            $taskScore = min($completedThisMonth / 5, 1) * 2.5;
            $onTimeScore = ($onTimeRate / 100) * 2.5;
            $productivityRating = $taskScore + $onTimeScore;
        }

        return view('developer.dashboard', compact(
            'myTasks',
            'currentTask',
            'activeTimeLog',
            'myProjects',
            'myTimeLogs',
            'completedTasks',
            'totalHoursWorked',
            'todoTasks',
            'todayTimeLogs',
            'todayTotalHours',
            'importantUpdates',
            'currentTaskDetail',
            'currentTaskComments',
            'completedThisMonth',
            'averageTimePerTask',
            'onTimeRate',
            'productivityRating'
        ));
    }

    /**
     * Block a card - mark task as blocked
     */
    public function blockCard(Card $card)
    {
        $user = Auth::user();

        DB::beginTransaction();
        try {
            // Find assignment (FIX: gunakan $card->id)
            $assignment = CardAssignment::where('card_id', $card->id)
                ->where('user_id', $user->id)
                ->first();

            if (!$assignment) {
                return back()->with('error', 'You are not assigned to this task!');
            }

            // End active time log if exists (FIX: gunakan $card->id)
            $activeTimeLog = TimeLog::where('user_id', $user->id)
                ->where('card_id', $card->id)
                ->whereNull('end_time')
                ->first();

            if ($activeTimeLog) {
                $endTime = now();
                $startTime = \Carbon\Carbon::parse($activeTimeLog->start_time);
                $durationMinutes = $startTime->diffInMinutes($endTime);
                
                $activeTimeLog->update([
                    'end_time' => $endTime,
                    'duration_minutes' => $durationMinutes
                ]);
                
                // Update card actual hours (FIX: gunakan $card->id)
                $totalMinutes = TimeLog::where('card_id', $card->id)
                    ->whereNotNull('duration_minutes')
                    ->sum('duration_minutes');
                $card->update(['actual_hours' => $totalMinutes / 60]);
            }

            // Update user status to idle (field sudah ada di database)
              $user->update(['current_task_status' => 'idle']); // ✅ No error

            // Update card status to blocker (sesuai ENUM database)
            $card->update(['status' => 'blocker']);

            // Add comment about blocking (FIX: gunakan $card->id)
            Comment::create([
                'card_id' => $card->id,
                'user_id' => $user->id,
                'comment_type' => 'card',
                'comment_text' => '[BLOCKER] Task blocked by ' . $user->full_name . '. Needs assistance.',
            ]);

            DB::commit();

            return back()->with('warning', 'Task has been blocked! Team Lead will be notified.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to block task: ' . $e->getMessage());
        }
    }

    /**
     * Start working on a task
     */
    public function startTask(Card $card)
    {
        $user = Auth::user();

        DB::beginTransaction();
        try {
            // Check if user is assigned to this card (FIX: gunakan $card->id)
            $assignment = CardAssignment::where('card_id', $card->id)
                ->where('user_id', $user->id)
                ->first();

            if (!$assignment) {
                return back()->with('error', 'You are not assigned to this task!');
            }

            // Check if user is already working on another task
            $existingWork = TimeLog::where('user_id', $user->id)
                ->whereNull('end_time')
                ->first();

            if ($existingWork) {
                return back()->with('error', 'Please pause your current task first!');
            }

            // Update user status to working
              $user->update(['current_task_status' => 'idle']); // ✅ No error

            // Update assignment status
            $assignment->update([
                'assignment_status' => 'in_progress',
                'started_at' => $assignment->started_at ?? now(),
            ]);

            // Update card status if it's still todo
            if ($card->status === 'todo') {
                $card->update(['status' => 'in_progress']);
            }

            // Create time log (FIX: gunakan $card->id)
            TimeLog::create([
                'card_id' => $card->id,
                'user_id' => $user->id,
                'start_time' => now(),
                'description' => 'Working on: ' . $card->card_title,
            ]);

            DB::commit();

            return back()->with('success', 'Started working on task!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to start task: ' . $e->getMessage());
        }
    }

    /**
     * Pause current work
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
                return back()->with('error', 'No active task to pause!');
            }

            // Calculate duration
            $endTime = now();
            $startTime = \Carbon\Carbon::parse($activeTimeLog->start_time);
            $durationMinutes = $startTime->diffInMinutes($endTime);

            // End time log
            $activeTimeLog->update([
                'end_time' => $endTime,
                'duration_minutes' => $durationMinutes
            ]);

            // Update user status to idle
            $user->update(['current_task_status' => 'idle']);

            // Update card actual hours
            $card = $activeTimeLog->card;
            $totalMinutes = TimeLog::where('card_id', $card->id)
                ->whereNotNull('duration_minutes')
                ->sum('duration_minutes');
            $card->update(['actual_hours' => $totalMinutes / 60]);

            DB::commit();

            return back()->with('success', 'Task paused successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to pause task: ' . $e->getMessage());
        }
    }

    /**
     * Complete current task
     */
    public function completeTask(Card $card)
    {
        $user = Auth::user();

        DB::beginTransaction();
        try {
            // Find assignment (FIX: gunakan $card->id)
            $assignment = CardAssignment::where('card_id', $card->id)
                ->where('user_id', $user->id)
                ->first();

            if (!$assignment) {
                return back()->with('error', 'You are not assigned to this task!');
            }

            // End active time log if exists (FIX: gunakan $card->id)
            $activeTimeLog = TimeLog::where('user_id', $user->id)
                ->where('card_id', $card->id)
                ->whereNull('end_time')
                ->first();

            if ($activeTimeLog) {
                $endTime = now();
                $startTime = \Carbon\Carbon::parse($activeTimeLog->start_time);
                $durationMinutes = $startTime->diffInMinutes($endTime);
                
                $activeTimeLog->update([
                    'end_time' => $endTime,
                    'duration_minutes' => $durationMinutes
                ]);
                
                // Update actual hours (FIX: gunakan $card->id)
                $totalMinutes = TimeLog::where('card_id', $card->id)
                    ->whereNotNull('duration_minutes')
                    ->sum('duration_minutes');
                $card->update(['actual_hours' => $totalMinutes / 60]);
            }

            // Update user status to idle
            $user->update(['current_task_status' => 'idle']); // ✅ No error

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
                ->route('developer.dashboard')
                ->with('success', 'Task completed! Great job! 🎉');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to complete task: ' . $e->getMessage());
        }
    }

    /**
     * View my time logs
     */
    public function timeLogs()
    {
        $user = Auth::user();

        $timeLogs = TimeLog::where('user_id', $user->id)
            ->with(['card.board.project'])
            ->latest()
            ->paginate(20);

        // Statistics
        $today = TimeLog::where('user_id', $user->id)
            ->whereDate('start_time', today())
            ->whereNotNull('duration_minutes')
            ->sum('duration_minutes') / 60;

        $thisWeek = TimeLog::where('user_id', $user->id)
            ->whereBetween('start_time', [now()->startOfWeek(), now()->endOfWeek()])
            ->whereNotNull('duration_minutes')
            ->sum('duration_minutes') / 60;

        $thisMonth = TimeLog::where('user_id', $user->id)
            ->whereMonth('start_time', now()->month)
            ->whereNotNull('duration_minutes')
            ->sum('duration_minutes') / 60;

        return view('developer.time-logs', compact('timeLogs', 'today', 'thisWeek', 'thisMonth'));
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
                $query->where('user_id', $user->id);
            })
            ->count();

        // Weekly chart data
        $weeklyHours = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $hours = TimeLog::where('user_id', $user->id)
                ->whereDate('start_time', $date)
                ->whereNotNull('duration_minutes')
                ->sum('duration_minutes') / 60;

            $weeklyHours[] = [
                'date' => $date->format('D'),
                'hours' => round($hours, 1)
            ];
        }

        return view('developer.statistics', compact(
            'totalTasks',
            'completedTasks',
            'inProgressTasks',
            'totalHours',
            'activeProjects',
            'weeklyHours'
        ));

        
    }

    /**
 * Show board detail (Read-only untuk developer)
 */
public function showBoard(Project $project, Board $board)
{
    $user = Auth::user();
    
    // Check if user is member of this project
    $isMember = $project->members()->where('user_id', $user->id)->exists();
    
    if (!$isMember) {
        return redirect()->route('developer.dashboard')
            ->with('error', 'You are not a member of this project!');
    }
    
    // Load board dengan relasi
    $board->load([
        'cards' => function($query) use ($user) {
            // Filter hanya cards yang assigned ke user ini atau semua cards (optional)
            $query->with(['assignments.user', 'subtasks', 'comments.user'])
                  ->orderBy('position');
        },
        'project.members'
    ]);
    
    // Get user's assignment di board ini
    $myAssignments = CardAssignment::whereIn('card_id', $board->cards->pluck('id'))
        ->where('user_id', $user->id)
        ->with('card')
        ->get();
    
    return view('developer.board-show', compact('board', 'project', 'myAssignments'));
}

}
