<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Card;
use App\Models\Comment;
use App\Models\Message;
use App\Models\Board;
use App\Models\CardAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TeamLeadController extends Controller
{
    /**
     * Show team lead dashboard
     */
    public function dashboard()
    {
        try {
            $userId = Auth::id();
            $user = Auth::user();

            $projects = Project::where(function($query) use ($userId) {
                    $query->where('created_by', $userId)
                        ->orWhereHas('members', function ($q) use ($userId) {
                            $q->where('project_members.user_id', $userId);
                        });
                })
                ->with([
                    'boards.cards.subtasks',
                    'boards.cards.assignments.user',
                    'members'
                ])
                ->orderBy('created_at', 'desc')
                ->get();

            $projects->each(function ($project) {
                $totalCards = $project->boards->sum(fn($b) => $b->cards->count());
                $doneCards = $project->boards->sum(fn($b) => $b->cards->where('status', 'done')->count());
                $project->progress = $totalCards > 0 ? round(($doneCards / $totalCards) * 100) : 0;
                $project->total_cards = $totalCards;
                $project->done_cards = $doneCards;

                if ($project->deadline) {
                    $daysLeft = now()->diffInDays($project->deadline, false);
                    $project->days_left = (int) $daysLeft;
                    $project->deadline_status = $daysLeft >= 0 ? 'active' : 'overdue';
                } else {
                    $project->days_left = null;
                    $project->deadline_status = null;
                }
            });

            $projectIds = $projects->pluck('id');

            if ($projectIds->isEmpty()) {
                $teamMembers = collect();
                $recentComments = collect();
            } else {
                $teamMembers = User::whereHas('projectMembers', function($query) use ($projectIds) {
                        $query->whereIn('project_members.project_id', $projectIds);
                    })
                    ->where('id', '!=', $userId)
                    ->with(['assignments' => function($query) use ($projectIds) {
                        $query->whereHas('card.board.project', function($q) use ($projectIds) {
                            $q->whereIn('projects.id', $projectIds);
                        })
                        ->whereIn('assignment_status', ['assigned', 'in_progress'])
                        ->with('card.board.project');
                    }])
                    ->get();

                foreach($teamMembers as $member) {
                    $activeAssignments = $member->assignments->where('assignment_status', 'in_progress');
                    $member->current_task = $activeAssignments->first()?->card;
                    $member->active_tasks_count = $member->assignments->count();
                }

                $recentComments = Comment::whereHas('card.board.project', function ($query) use ($projectIds) {
                        $query->whereIn('projects.id', $projectIds);
                    })
                    ->with(['user', 'card.board.project'])
                    ->latest()
                    ->take(10)
                    ->get();
            }

            $totalTasks = 0;
            $completedTasks = 0;
            $todoTasks = 0;
            $inProgressTasks = 0;
            $reviewTasks = 0;
            $blockerTasks = 0;

            foreach ($projects as $project) {
                foreach ($project->boards as $board) {
                    $cards = $board->cards;
                    $totalTasks += $cards->count();
                    $completedTasks += $cards->where('status', 'done')->count();
                    $todoTasks += $cards->where('status', 'todo')->count();
                    $inProgressTasks += $cards->where('status', 'in_progress')->count();
                    $reviewTasks += $cards->where('status', 'review')->count();
                    $blockerTasks += $cards->where('status', 'blocker')->count();
                }
            }

            $doneTasks = $completedTasks;
            $activeMembers = $teamMembers->filter(fn($m) => $m->active_tasks_count > 0)->count();
            $totalMembers = $teamMembers->count();

            return view('teamlead.dashboard', compact(
                'projects',
                'teamMembers',
                'totalTasks',
                'completedTasks',
                'todoTasks',
                'inProgressTasks',
                'reviewTasks',
                'blockerTasks',
                'doneTasks',
                'recentComments',
                'activeMembers',
                'totalMembers'
            ));

        } catch (\Exception $e) {
            \Log::error('Team Lead Dashboard Error: ' . $e->getMessage());

            return view('teamlead.dashboard', [
                'projects' => collect(),
                'teamMembers' => collect(),
                'totalTasks' => 0,
                'completedTasks' => 0,
                'todoTasks' => 0,
                'inProgressTasks' => 0,
                'reviewTasks' => 0,
                'blockerTasks' => 0,
                'doneTasks' => 0,
                'recentComments' => collect(),
                'activeMembers' => 0,
                'totalMembers' => 0,
                'error' => 'Failed to load dashboard data. Please contact administrator.'
            ]);
        }
    }

    public function projects()
    {
        $user = Auth::user();

        $projects = Project::with(['boards.cards', 'members', 'creator'])
            ->where(function($query) use ($user) {
                $query->where('projects.created_by', $user->id)
                    ->orWhereHas('members', function($q) use ($user) {
                        $q->where('project_members.user_id', $user->id)
                            ->whereIn('project_members.role', ['super_admin', 'admin']);
                    });
            })
            ->latest()
            ->paginate(12);

        return view('teamlead.projects.index', compact('projects'));
    }

    public function showProjectCards(Project $project)
    {
        $user = Auth::user();

        if (!$this->userHasProjectAccess($user, $project)) {
            abort(403, 'Unauthorized access to this project.');
        }

        $project->load([
            'boards.cards.assignments.user',
            'boards.cards.comments.user',
            'boards.cards.subtasks',
            'boards.cards.creator'
        ]);

        $allCards = $project->boards->flatMap(function($board) {
            return $board->cards;
        });

        $developers = $project->members()
            ->whereIn('users.role', ['developer', 'designer'])
            ->get();

        return view('teamlead.projects.cards', compact('project', 'allCards', 'developers'));
    }

    public function showBoard(Project $project, Board $board)
    {
        if ($board->project_id != $project->id) {
            abort(404, 'Board not found in this project');
        }

        $user = Auth::user();
        $hasAccess = $project->members()
            ->where('project_members.user_id', $user->id)
            ->exists();

        if (!$hasAccess && $project->created_by != $user->id) {
            abort(403, 'You do not have access to this board');
        }

        $board->load([
            'cards.assignments.user',
            'cards.comments.user',
            'cards.subtasks'
        ]);

        $developers = $project->members()
            ->whereIn('users.role', ['developer', 'designer'])
            ->get();

        \Log::info('=== BOARD SHOW DEBUG ===');
        \Log::info('Board ID: ' . $board->id);
        \Log::info('Project ID: ' . $project->id);
        \Log::info('Developers count: ' . $developers->count());

        return view('teamlead.boards.show', compact('project', 'board', 'developers'));
    }

    public function showProject(Project $project)
    {
        $user = Auth::user();

        if (!$this->userHasProjectAccess($user, $project)) {
            abort(403, 'Unauthorized access to this project.');
        }

        $project->load([
            'boards.cards.subtasks',
            'boards.cards.assignments.user',
            'boards.cards.comments.user',
            'members',
            'creator'
        ]);

        $developers = $project->members()
            ->whereIn('users.role', ['developer', 'designer'])
            ->get();

        \Log::info('=== PROJECT SHOW DEBUG ===');
        \Log::info('Project ID: ' . $project->id);
        \Log::info('Developers Count: ' . $developers->count());

        $stats = $this->calculateProjectStats($project);

        return view('teamlead.projects.show', compact('project', 'stats', 'developers'));
    }

    public function show($id)
    {
        $project = Project::with([
            'boards.cards.subtasks',
            'boards.cards.assignments.user',
            'boards.cards.comments.user',
            'members'
        ])->findOrFail($id);

        $user = Auth::user();

        if (!$this->userHasProjectAccess($user, $project)) {
            abort(403, 'Unauthorized access to this project.');
        }

        $developers = $project->members()
            ->whereIn('users.role', ['developer', 'designer'])
            ->get();

        $stats = $this->calculateProjectStats($project);

        return view('teamlead.projects.show', compact('project', 'stats', 'developers'));
    }

    public function review()
    {
        $user = Auth::user();

        $projects = Project::where(function($query) use ($user) {
                $query->where('projects.created_by', $user->id)
                    ->orWhereHas('members', function($q) use ($user) {
                        $q->where('project_members.user_id', $user->id)
                            ->whereIn('project_members.role', ['super_admin', 'admin']);
                    });
            })
            ->with(['boards.cards.assignments.user', 'boards.cards.subtasks'])
            ->get();

        $pendingCards = collect();
        foreach ($projects as $project) {
            foreach ($project->boards as $board) {
                $cards = $board->cards->where('status', 'review');
                $pendingCards = $pendingCards->merge($cards);
            }
        }

        $approvedToday = 0;
        foreach ($projects as $project) {
            foreach ($project->boards as $board) {
                $approvedToday += $board->cards
                    ->where('status', 'done')
                    ->filter(function($card) {
                        return $card->updated_at->isToday();
                    })
                    ->count();
            }
        }

        $rejectedToday = 0;
        foreach ($projects as $project) {
            foreach ($project->boards as $board) {
                $rejectedToday += $board->cards
                    ->whereIn('status', ['in_progress', 'todo'])
                    ->filter(function($card) {
                        return $card->updated_at->isToday();
                    })
                    ->count();
            }
        }

        $recentlyReviewed = collect();
        foreach ($projects as $project) {
            foreach ($project->boards as $board) {
                $cards = $board->cards
                    ->where('status', 'done')
                    ->sortByDesc('updated_at')
                    ->take(5);
                $recentlyReviewed = $recentlyReviewed->merge($cards);
            }
        }
        $recentlyReviewed = $recentlyReviewed->sortByDesc('updated_at')->take(5);

        return view('teamlead.review.index', compact(
            'projects',
            'pendingCards',
            'approvedToday',
            'rejectedToday',
            'recentlyReviewed'
        ));
    }

    public function approveTask(Card $card)
    {
        $user = Auth::user();

        $hasAccess = $card->board->project->members()
            ->where('project_members.user_id', $user->id)
            ->whereIn('project_members.role', ['super_admin', 'admin'])
            ->exists();

        if (!$hasAccess && $card->board->project->created_by != $user->id) {
            abort(403, 'You do not have permission to approve this task');
        }

        try {
            $card->update([
                'status' => 'done',
                'updated_at' => now()
            ]);

            return back()->with('success', 'Task approved successfully!');
        } catch (\Exception $e) {
            \Log::error('Approve Task Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to approve task: ' . $e->getMessage());
        }
    }

    public function rejectTask(Request $request, Card $card)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $user = Auth::user();

        $hasAccess = $card->board->project->members()
            ->where('project_members.user_id', $user->id)
            ->whereIn('project_members.role', ['super_admin', 'admin'])
            ->exists();

        if (!$hasAccess && $card->board->project->created_by != $user->id) {
            abort(403, 'You do not have permission to reject this task');
        }

        try {
            $card->update([
                'status' => 'in_progress',
                'updated_at' => now()
            ]);

            Comment::create([
                'card_id' => $card->id,
                'user_id' => $user->id,
                'comment' => '🔴 **REJECTED** - ' . $validated['reason']
            ]);

            return back()->with('success', 'Task rejected and sent back for revision.');
        } catch (\Exception $e) {
            \Log::error('Reject Task Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to reject task: ' . $e->getMessage());
        }
    }

    public function reviewDetail(Card $card)
    {
        $user = Auth::user();

        if (!$this->userHasProjectAccess($user, $card->board->project)) {
            abort(403, 'Unauthorized access.');
        }

        $card->load([
            'assignments.user',
            'comments.user',
            'subtasks',
            'board.project'
        ]);

        return view('teamlead.review.detail', compact('card'));
    }

    public function reports()
    {
        $user = Auth::user();

        $projects = Project::with('boards.cards')
            ->where(function($query) use ($user) {
                $query->where('projects.created_by', $user->id)
                    ->orWhereHas('members', function($q) use ($user) {
                        $q->where('project_members.user_id', $user->id)
                            ->whereIn('project_members.role', ['super_admin', 'admin']);
                    });
            })
            ->get();

        $stats = [
            'total_projects' => $projects->count(),
            'total_tasks' => $projects->sum(fn($p) => $p->boards->sum(fn($b) => $b->cards->count())),
            'completed_tasks' => $projects->sum(fn($p) => $p->boards->sum(fn($b) => $b->cards->where('status', 'done')->count())),
            'avg_progress' => $projects->avg('progress') ?? 0,
        ];

        return view('teamlead.reports.index', compact('projects', 'stats'));
    }

    public function showReport(Project $project)
    {
        $user = Auth::user();

        if (!$this->userHasProjectAccess($user, $project)) {
            abort(403, 'Unauthorized access to this project.');
        }

        $project->load('boards.cards.assignments.user', 'members');

        $stats = $this->calculateProjectStats($project);
        $teamPerformance = $this->getTeamPerformance($project);

        return view('teamlead.reports.show', compact('project', 'stats', 'teamPerformance'));
    }

    public function projectDetail(Project $project)
    {
        $user = Auth::user();

        if (!$this->userHasProjectAccess($user, $project)) {
            abort(403, 'Unauthorized access to this project.');
        }

        $project->load([
            'boards.cards.assignments.user',
            'boards.cards.comments.user',
            'members'
        ]);

        $teamPerformance = $this->getTeamPerformance($project);

        return view('teamlead.project-detail', compact('project', 'teamPerformance'));
    }

    public function teamReport()
    {
        $user = Auth::user();

        $projects = Project::where('projects.created_by', $user->id)
            ->orWhereHas('members', function($q) use ($user) {
                $q->where('project_members.user_id', $user->id)
                    ->whereIn('project_members.role', ['super_admin', 'admin']);
            })
            ->get();

        $projectIds = $projects->pluck('id');

        $teamMembers = User::whereHas('projectMembers', function($query) use ($projectIds) {
            $query->whereIn('project_id', $projectIds);
        })
        ->where('id', '!=', $user->id)
        ->get()
        ->map(function($member) {
            $totalHours = 0;
            $thisWeekHours = 0;

            if (method_exists($member, 'timeLogs')) {
                $totalHours = $member->timeLogs()
                    ->whereNotNull('duration_minutes')
                    ->sum('duration_minutes') / 60;

                $thisWeekHours = $member->timeLogs()
                    ->whereBetween('start_time', [now()->startOfWeek(), now()->endOfWeek()])
                    ->sum('duration_minutes') / 60;
            }

            $totalTasks = 0;
            $completedTasks = 0;

            if (method_exists($member, 'assignments')) {
                $totalTasks = $member->assignments()->count();
                $completedTasks = $member->assignments()
                    ->where('assignment_status', 'completed')
                    ->count();
            }

            $member->total_tasks = $totalTasks;
            $member->completed_tasks = $completedTasks;
            $member->total_hours = $totalHours;
            $member->this_week_hours = $thisWeekHours;

            return $member;
        });

        return view('teamlead.team-report', compact('teamMembers'));
    }

    public function taskAssignments()
    {
        $user = Auth::user();

        $projects = Project::where('projects.created_by', $user->id)
            ->orWhereHas('members', function($q) use ($user) {
                $q->where('project_members.user_id', $user->id)
                    ->whereIn('project_members.role', ['super_admin', 'admin']);
            })
            ->with('boards.cards.assignments.user')
            ->get();

        $cards = $projects->flatMap->boards->flatMap->cards;

        $unassigned = $cards->filter(function($card) {
            return $card->assignments->count() === 0;
        });

        $assigned = $cards->filter(function($card) {
            return $card->assignments->count() > 0;
        });

        return view('teamlead.task-assignments', compact('unassigned', 'assigned'));
    }

    public function assignTask(Request $request, Card $card)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $hasAccess = $card->board->project->members()
            ->where('project_members.user_id', auth()->id())
            ->exists();

        if (!$hasAccess && $card->board->project->created_by != auth()->id()) {
            abort(403, 'You do not have permission to assign this task');
        }

        try {
            foreach ($validated['user_ids'] as $userId) {
                $exists = CardAssignment::where('card_id', $card->id)
                    ->where('user_id', $userId)
                    ->exists();

                if (!$exists) {
                    CardAssignment::create([
                        'card_id' => $card->id,
                        'user_id' => $userId,
                        'assignment_status' => 'assigned',
                    ]);
                }
            }

            return back()->with('success', 'Task assigned successfully!');
        } catch (\Exception $e) {
            \Log::error('Assign Task Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to assign task: ' . $e->getMessage());
        }
    }

    public function removeAssignment(Card $card, $userId)
    {
        $hasAccess = $card->board->project->members()
            ->where('project_members.user_id', auth()->id())
            ->exists();

        if (!$hasAccess && $card->board->project->created_by != auth()->id()) {
            abort(403, 'You do not have permission to modify assignments');
        }

        try {
            CardAssignment::where('card_id', $card->id)
                ->where('user_id', $userId)
                ->delete();

            return back()->with('success', 'Assignment removed successfully!');
        } catch (\Exception $e) {
            \Log::error('Remove Assignment Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to remove assignment: ' . $e->getMessage());
        }
    }

    protected function userHasProjectAccess(User $user, Project $project)
    {
        if ($project->created_by == $user->id) {
            return true;
        }

        $member = $project->members()
            ->where('project_members.user_id', $user->id)
            ->first();

        return $member !== null;
    }

    protected function calculateProjectStats(Project $project)
    {
        return [
            'total_tasks' => $project->boards->sum(fn($b) => $b->cards->count()),
            'todo' => $project->boards->sum(fn($b) => $b->cards->where('status', 'todo')->count()),
            'in_progress' => $project->boards->sum(fn($b) => $b->cards->where('status', 'in_progress')->count()),
            'review' => $project->boards->sum(fn($b) => $b->cards->where('status', 'review')->count()),
            'done' => $project->boards->sum(fn($b) => $b->cards->where('status', 'done')->count()),
        ];
    }

    protected function getTeamPerformance(Project $project)
    {
        $projectId = $project->id;

        return $project->members->map(function($member) use ($projectId) {
            $assignments = CardAssignment::whereHas('card.board', function($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })
            ->where('user_id', $member->id)
            ->get();

            $totalHours = 0;
            if (method_exists($member, 'timeLogs')) {
                $totalHours = $member->timeLogs()
                    ->whereHas('card.board', function($query) use ($projectId) {
                        $query->where('project_id', $projectId);
                    })
                    ->sum('duration_minutes') / 60;
            }

            return [
                'member' => $member,
                'total_tasks' => $assignments->count(),
                'completed_tasks' => $assignments->where('assignment_status', 'completed')->count(),
                'in_progress_tasks' => $assignments->where('assignment_status', 'in_progress')->count(),
                'total_hours' => $totalHours,
            ];
        });
    }
}
