<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\CardAssignment;
use App\Models\Comment;
use App\Models\TimeLog;
use App\Models\Project;
use App\Models\Board;
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
            ->with(['boards.cards', 'members'])
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

        // Todo Tasks
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

        // Current Task Detail
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

        // Today Time Logs
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

        // Important Updates
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

        // ✅ ACHIEVEMENT STATS - This Month Performance
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

        // ✅ On-Time Rate Calculation
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

        // ✅ Productivity Rating (0-5 scale)
        $productivityRating = 0;
        if ($completedThisMonth > 0) {
            $taskScore = min($completedThisMonth / 10, 1) * 2.5; // Max 2.5 from task count
            $onTimeScore = ($onTimeRate / 100) * 2.5; // Max 2.5 from on-time rate
            $productivityRating = $taskScore + $onTimeScore;
        }

        // ✅ Overall Rating (0-5 scale)
        $rating = $this->calculateDeveloperRating($user->id);
        
        // ✅ Quality Score (percentage)
        $qualityScore = $this->calculateQualityScore($user->id);
        
        // ✅ Response Time
        $avgResponseTime = $this->calculateResponseTime($user->id);

        // ✅ Get cards by status untuk display (FIXED)
        $myCards = Card::whereHas('assignments', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['board.project', 'assignments.user', 'subtasks', 'timeLogs'])
        ->whereIn('status', ['todo', 'in_progress', 'review', 'done'])
        ->orderBy('updated_at', 'desc')
        ->get();

        return view('developer.dashboard', compact(
            'myTasks',
            'currentTask',
            'activeTimeLog',
            'myProjects',
            'myTimeLogs',
            'myCards',
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
            'productivityRating',
            'rating',
            'qualityScore',
            'avgResponseTime'
        ));
    }

    /**
     * ✅ Calculate overall developer rating (0-5 scale)
     */
    private function calculateDeveloperRating($userId)
    {
        $thisMonth = now()->month;
        $thisYear = now()->year;

        // Factor 1: On-Time Rate (40% weight = 2 points)
        $completedWithDeadline = CardAssignment::where('user_id', $userId)
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
                if (\Carbon\Carbon::parse($assignment->completed_at)->lte(\Carbon\Carbon::parse($assignment->card->due_date))) {
                    $onTimeCount++;
                }
            }
        }

        $onTimeRate = $completedWithDeadline->count() > 0
            ? ($onTimeCount / $completedWithDeadline->count())
            : 0;

        $onTimeScore = $onTimeRate * 2;

        // Factor 2: Task Completion Rate (30% weight = 1.5 points)
        $totalAssigned = CardAssignment::where('user_id', $userId)
            ->whereYear('created_at', $thisYear)
            ->whereMonth('created_at', $thisMonth)
            ->count();

        $totalCompleted = CardAssignment::where('user_id', $userId)
            ->where('assignment_status', 'completed')
            ->whereYear('completed_at', $thisYear)
            ->whereMonth('completed_at', $thisMonth)
            ->count();

        $completionRate = $totalAssigned > 0 ? ($totalCompleted / $totalAssigned) : 0;
        $completionScore = $completionRate * 1.5;

        // Factor 3: Quality (30% weight = 1.5 points)
        $approvedCount = Comment::where('comment_text', 'like', '%[APPROVED]%')
            ->whereHas('card.assignments', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->whereYear('created_at', $thisYear)
            ->whereMonth('created_at', $thisMonth)
            ->count();

        $rejectedCount = Comment::where('comment_text', 'like', '%[REJECTED]%')
            ->whereHas('card.assignments', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->whereYear('created_at', $thisYear)
            ->whereMonth('created_at', $thisMonth)
            ->count();

        $qualityRate = ($approvedCount + $rejectedCount) > 0
            ? ($approvedCount / ($approvedCount + $rejectedCount))
            : 0.5;

        $qualityScore = $qualityRate * 1.5;

        // Total Rating (max 5.0)
        $rating = $onTimeScore + $completionScore + $qualityScore;

        return round($rating, 1);
    }

    /**
     * ✅ Calculate quality score percentage
     */
    private function calculateQualityScore($userId)
    {
        $thisMonth = now()->month;
        $thisYear = now()->year;

        $approvedCount = Comment::where('comment_text', 'like', '%[APPROVED]%')
            ->whereHas('card.assignments', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->whereYear('created_at', $thisYear)
            ->whereMonth('created_at', $thisMonth)
            ->count();

        $totalReviews = Comment::where(function($query) {
                $query->where('comment_text', 'like', '%[APPROVED]%')
                      ->orWhere('comment_text', 'like', '%[REJECTED]%');
            })
            ->whereHas('card.assignments', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->whereYear('created_at', $thisYear)
            ->whereMonth('created_at', $thisMonth)
            ->count();

        return $totalReviews > 0 ? round(($approvedCount / $totalReviews) * 100) : 0;
    }

    /**
     * Calculate average response time (hours)
     */
    private function calculateResponseTime($userId)
    {
        $assignments = CardAssignment::where('user_id', $userId)
            ->where('assignment_status', 'in_progress')
            ->whereNotNull('started_at')
            ->get();

        if ($assignments->isEmpty()) {
            return 0;
        }

        $totalHours = 0;
        foreach ($assignments as $assignment) {
            $assigned = \Carbon\Carbon::parse($assignment->created_at);
            $started = \Carbon\Carbon::parse($assignment->started_at);
            $totalHours += $assigned->diffInHours($started);
        }

        return round($totalHours / $assignments->count(), 1);
    }

    /**
     * Block a card - mark task as blocked
     */
    public function blockCard(Card $card)
    {
        $user = Auth::user();

        DB::beginTransaction();
        try {
            $assignment = CardAssignment::where('card_id', $card->id)
                ->where('user_id', $user->id)
                ->first();

            if (!$assignment) {
                return back()->with('error', 'You are not assigned to this task!');
            }

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

                $totalMinutes = TimeLog::where('card_id', $card->id)
                    ->whereNotNull('duration_minutes')
                    ->sum('duration_minutes');
                $card->update(['actual_hours' => $totalMinutes / 60]);
            }

            $user->update(['current_task_status' => 'idle']);
            $card->update(['status' => 'blocker']);

            Comment::create([
                'card_id' => $card->id,
                'user_id' => $user->id,
                'comment_type' => 'card',
                'comment_text' => '[BLOCKER] Task blocked by ' . ($user->full_name ?: $user->username) . '. Needs assistance.',
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
            $assignment = CardAssignment::where('card_id', $card->id)
                ->where('user_id', $user->id)
                ->first();

            if (!$assignment) {
                return back()->with('error', 'You are not assigned to this task!');
            }

            $existingWork = TimeLog::where('user_id', $user->id)
                ->whereNull('end_time')
                ->first();

            if ($existingWork) {
                return back()->with('error', 'Please pause your current task first!');
            }

            $user->update(['current_task_status' => 'working']);

            $assignment->update([
                'assignment_status' => 'in_progress',
                'started_at' => $assignment->started_at ?? now(),
            ]);

            if ($card->status === 'todo') {
                $card->update(['status' => 'in_progress']);
            }

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
            $activeTimeLog = TimeLog::where('user_id', $user->id)
                ->whereNull('end_time')
                ->first();

            if (!$activeTimeLog) {
                return back()->with('error', 'No active task to pause!');
            }

            $endTime = now();
            $startTime = \Carbon\Carbon::parse($activeTimeLog->start_time);
            $durationMinutes = $startTime->diffInMinutes($endTime);

            $activeTimeLog->update([
                'end_time' => $endTime,
                'duration_minutes' => $durationMinutes
            ]);

            $user->update(['current_task_status' => 'idle']);

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
     * ✅ Complete task & submit for Team Lead review
     */
    public function completeTask(Card $card)
    {
        $user = Auth::user();

        DB::beginTransaction();
        try {
            $assignment = CardAssignment::where('card_id', $card->id)
                ->where('user_id', $user->id)
                ->first();

            if (!$assignment) {
                return back()->with('error', 'You are not assigned to this task!');
            }

            if ($card->status !== 'in_progress') {
                return back()->with('error', 'Task must be in progress to complete!');
            }

            // Stop active timer
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

                $totalMinutes = TimeLog::where('card_id', $card->id)
                    ->whereNotNull('duration_minutes')
                    ->sum('duration_minutes');
                $card->update(['actual_hours' => $totalMinutes / 60]);
            }

            $user->update(['current_task_status' => 'idle']);

            // ✅ Status jadi REVIEW (bukan done)
            $card->update(['status' => 'review']);

            // ✅ Add submission comment
            Comment::create([
                'card_id' => $card->id,
                'user_id' => $user->id,
                'comment_type' => 'card',
                'comment_text' => 'Task completed and submitted for review by ' . ($user->full_name ?: $user->username),
            ]);

            DB::commit();

            return redirect()
                ->route('developer.dashboard')
                ->with('success', 'Task submitted for Team Lead review! 🎉');

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

        $totalTasks = CardAssignment::where('user_id', $user->id)->count();
        $completedTasks = CardAssignment::where('user_id', $user->id)
            ->where('assignment_status', 'completed')
            ->count();
        $inProgressTasks = CardAssignment::where('user_id', $user->id)
            ->where('assignment_status', 'in_progress')
            ->count();

        $totalHours = TimeLog::where('user_id', $user->id)
            ->whereNotNull('duration_minutes')
            ->sum('duration_minutes') / 60;

        $activeProjects = Project::whereHas('members', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->count();

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

        $isMember = $project->members()->where('user_id', $user->id)->exists();

        if (!$isMember) {
            return redirect()->route('developer.dashboard')
                ->with('error', 'You are not a member of this project!');
        }

        $board->load([
            'cards' => function($query) use ($user) {
                $query->with(['assignments.user', 'subtasks', 'comments.user'])
                      ->orderBy('position');
            },
            'project.members'
        ]);

        $myAssignments = CardAssignment::whereIn('card_id', $board->cards->pluck('id'))
            ->where('user_id', $user->id)
            ->with('card')
            ->get();

        return view('developer.board-show', compact('board', 'project', 'myAssignments'));
    }
}
