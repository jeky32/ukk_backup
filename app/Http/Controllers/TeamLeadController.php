<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Models\Comment;
use App\Models\Card;
use App\Models\CardAssignment;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class TeamLeadController extends Controller
{
    /**
     * Show team lead dashboard
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $userId = Auth::id();
        $user = Auth::user();

        // Get all projects where user is team lead
        $projects = Project::where('created_by', $userId)
            ->orWhereHas('members', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->whereIn('role', ['admin', 'super_admin', 'teamlead']);
            })
            ->with(['boards.cards.subtasks', 'boards.cards.assignedMembers', 'members'])
            ->get();

        // Calculate progress for each project
        $projects->each(function ($project) {
            $totalCards = $project->boards->sum(fn($b) => $b->cards->count());
            $doneCards = $project->boards->sum(fn($b) => $b->cards->where('status', 'done')->count());
            $project->progress = $totalCards > 0 ? round(($doneCards / $totalCards) * 100) : 0;
            $project->total_cards = $totalCards;
            $project->done_cards = $doneCards;

            if ($project->deadline) {
                $daysLeft = now()->diffInDays($project->deadline, false);
                $project->days_left = (int)$daysLeft;
                $project->deadline_status = $daysLeft > 0 ? 'active' : 'overdue';
            } else {
                $project->days_left = null;
                $project->deadline_status = null;
            }
        });

        // Get all team members from all projects - FIXED relationship name
        $projectIds = $projects->pluck('id');

        $teamMembers = User::whereHas('projectMembers', function($query) use ($projectIds) {
            $query->whereIn('project_id', $projectIds);
        })->with(['assignedCards' => function($query) {
            $query->whereIn('status', ['todo', 'in_progress', 'review']);
        }])->get();

        // Add current task info to team members
        foreach($teamMembers as $member) {
            $member->current_task = $member->assignedCards->first();
            $member->active_tasks_count = $member->assignedCards->count();
        }

        // Get recent comments
        $recentComments = Comment::whereHas('card.board.project', function ($query) use ($projectIds) {
            $query->whereIn('projects.id', $projectIds);
        })
            ->with(['user', 'card.board.project'])
            ->latest()
            ->take(10)
            ->get();

        // Calculate statistics
        $totalTasks = $projects->sum('total_cards');
        $completedTasks = $projects->sum('done_cards');
        $todoTasks = $projects->sum(fn($p) => $p->boards->sum(fn($b) => $b->cards->where('status', 'todo')->count()));
        $inProgressTasks = $projects->sum(fn($p) => $p->boards->sum(fn($b) => $b->cards->where('status', 'in_progress')->count()));
        $reviewTasks = $projects->sum(fn($p) => $p->boards->sum(fn($b) => $b->cards->where('status', 'review')->count()));
        $doneTasks = $completedTasks;

        return view('teamlead.dashboard', compact(
            'projects',
            'teamMembers',
            'totalTasks',
            'completedTasks',
            'todoTasks',
            'inProgressTasks',
            'reviewTasks',
            'doneTasks',
            'recentComments'
        ));
    }

    public function monitoring()
    {
        try {
            // Load projects dengan relationships yang diperlukan
            $userId = Auth::id();
            $user = Auth::user();

            // Get all projects where user is team lead
            $projects = Project::where('created_by', $userId)
                ->orWhereHas('members', function ($query) use ($userId) {
                    $query->where('user_id', $userId)
                        ->whereIn('role', ['admin', 'super_admin', 'teamlead']);
                })
                ->with(['boards.cards.subtasks', 'boards.cards.assignedMembers', 'boards.cards.assignedUser', 'boards.cards.comments.user', 'members'])
                ->get();

            // ✅ SESUAIKAN DENGAN VIEW - GUNAKAN $projectStats BUKAN $statistics
            $projectStats = [
                'total' => $projects->count(),
                'with_deadline' => $projects->where('deadline', '!=', null)->count(),
                'deadline_approaching' => $projects->filter(function($project) {
                    return $project->deadline &&
                        Carbon::parse($project->deadline)->isFuture() &&
                        now()->diffInDays(Carbon::parse($project->deadline), false) <= 7;
                })->count(),
                'overdue' => $projects->filter(function($project) {
                    return $project->deadline &&
                        Carbon::parse($project->deadline)->isPast();
                })->count()
            ];

            // ✅ HITUNG USER STATISTICS UNTUK VIEW - SESUAIKAN DENGAN FIELD YANG ADA
            $totalUsers = User::whereIn('role', ['developer', 'designer', 'teamlead'])->count();
            $workingUsers = User::whereIn('role', ['developer', 'designer', 'teamlead'])
                            ->where('current_task_status', 'working')
                            ->count();
            $idleUsers = $totalUsers - $workingUsers;

            // ✅ HITUNG MEMBER DISTRIBUTION
            $memberDistribution = [];
            foreach ($projects as $project) {
                $memberDistribution[$project->project_name] = $project->members->count();
            }

            // ✅ GET BLOCKED CARDS
            $blockedCards = collect();
            foreach ($projects as $project) {
                foreach ($project->boards as $board) {
                    $blocked = $board->cards()
                        ->where('status', 'blocker')
                        ->with(['assignedUser', 'board.project'])
                        ->get();
                    $blockedCards = $blockedCards->merge($blocked);
                }
            }

            // ✅ NEW: GET PRIORITY TASKS PER PROJECT (HIGH & MEDIUM)
            $priorityTasksByProject = [];
            foreach ($projects as $project) {
                $priorityTasks = collect();
                foreach ($project->boards as $board) {
                    $tasks = $board->cards()
                        ->whereIn('priority', ['high', 'medium'])
                        ->with(['assignedUser', 'board'])
                        ->get();
                    $priorityTasks = $priorityTasks->merge($tasks);
                }
                
                // Sort by priority: high first, then medium
                $priorityTasksByProject[$project->id] = $priorityTasks->sortBy(function($card) {
                    return $card->priority === 'high' ? 0 : 1;
                })->take(10); // Limit to 10 tasks per project
            }

            // ✅ NEW: GET RECENT ACTIVITY (COMMENTS) PER PROJECT
            $recentActivitiesByProject = [];
            foreach ($projects as $project) {
                $activities = collect();
                foreach ($project->boards as $board) {
                    foreach ($board->cards as $card) {
                        $comments = $card->comments()
                            ->where(function($query) {
                                $query->where('comment_text', 'LIKE', '%[APPROVED]%')
                                    ->orWhere('comment_text', 'LIKE', '%[REJECTED]%')
                                    ->orWhere('comment_text', 'LIKE', '%[BLOCKED]%')
                                    ->orWhere('comment_text', 'LIKE', '%[BLOCKER]%')
                                    ->orWhere('comment_text', 'LIKE', '%[REVIEW]%');
                            })
                            ->with(['user', 'card'])
                            ->get();
                        $activities = $activities->merge($comments);
                    }
                }
                
                // Sort by created_at descending (newest first) and take 10
                $recentActivitiesByProject[$project->id] = $activities->sortByDesc('created_at')->take(10);
            }

            return view('teamlead.monitoring', compact(
                'projects',
                'projectStats',
                'workingUsers',
                'idleUsers',
                'memberDistribution',
                'blockedCards',
                'priorityTasksByProject',      // ✅ NEW
                'recentActivitiesByProject'    // ✅ NEW
            ));

        } catch (\Exception $e) {
            // Fallback jika ada error
            $projects = Project::all();

            $projectStats = [
                'total' => $projects->count(),
                'with_deadline' => 0,
                'deadline_approaching' => 0,
                'overdue' => 0
            ];

            $workingUsers = 0;
            $idleUsers = 0;
            $memberDistribution = [];
            $blockedCards = collect();
            $priorityTasksByProject = [];
            $recentActivitiesByProject = [];

            return view('teamlead.monitoring', compact(
                'projects',
                'projectStats',
                'workingUsers',
                'idleUsers',
                'memberDistribution',
                'blockedCards',
                'priorityTasksByProject',
                'recentActivitiesByProject'
            ));
        }
    }

	// public function review()
    // {
        // $userId = Auth::id();
        // $user = Auth::user();

        // // Get all projects where user is team lead
        // $projects = Project::where('created_by', $userId)
            // ->orWhereHas('members', function ($query) use ($userId) {
                // $query->where('user_id', $userId)
                    // ->whereIn('role', ['admin', 'super_admin', 'teamlead']);
            // })
            // ->with(['boards.cards.subtasks', 'boards.cards.assignedMembers', 'members'])
            // ->get();

        // // Calculate progress for each project
        // $projects->each(function ($project) {
            // $totalCards = $project->boards->sum(fn($b) => $b->cards->count());
            // $doneCards = $project->boards->sum(fn($b) => $b->cards->where('status', 'done')->count());
            // $project->progress = $totalCards > 0 ? round(($doneCards / $totalCards) * 100) : 0;
            // $project->total_cards = $totalCards;
            // $project->done_cards = $doneCards;

            // if ($project->deadline) {
                // $daysLeft = now()->diffInDays($project->deadline, false);
                // $project->days_left = (int)$daysLeft;
                // $project->deadline_status = $daysLeft > 0 ? 'active' : 'overdue';
            // } else {
                // $project->days_left = null;
                // $project->deadline_status = null;
            // }
        // });

        // // Get all team members from all projects - FIXED relationship name
        // $projectIds = $projects->pluck('id');

        // $teamMembers = User::whereHas('projectMembers', function($query) use ($projectIds) {
            // $query->whereIn('project_id', $projectIds);
        // })->with(['assignedCards' => function($query) {
            // $query->whereIn('status', ['todo', 'in_progress', 'review']);
        // }])->get();

        // // Add current task info to team members
        // foreach($teamMembers as $member) {
            // $member->current_task = $member->assignedCards->first();
            // $member->active_tasks_count = $member->assignedCards->count();
        // }

        // // Get recent comments
        // $recentComments = Comment::whereHas('card.board.project', function ($query) use ($projectIds) {
            // $query->whereIn('projects.id', $projectIds);
        // })
            // ->with(['user', 'card.board.project'])
            // ->latest()
            // ->take(10)
            // ->get();

        // // Calculate statistics
        // $totalTasks = $projects->sum('total_cards');
        // $completedTasks = $projects->sum('done_cards');
        // $todoTasks = $projects->sum(fn($p) => $p->boards->sum(fn($b) => $b->cards->where('status', 'todo')->count()));
        // $inProgressTasks = $projects->sum(fn($p) => $p->boards->sum(fn($b) => $b->cards->where('status', 'in_progress')->count()));
        // $reviewTasks = $projects->sum(fn($p) => $p->boards->sum(fn($b) => $b->cards->where('status', 'review')->count()));
        // $doneTasks = $completedTasks;

        // return view('teamlead.reviw', compact(
            // 'projects',
            // 'teamMembers',
            // 'totalTasks',
            // 'completedTasks',
            // 'todoTasks',
            // 'inProgressTasks',
            // 'reviewTasks',
            // 'doneTasks',
            // 'recentComments'
        // ));
    // }

	public function review()
	{
		$userId = Auth::id();
		$user = Auth::user();

		// Ambil semua cards dengan status 'review' dari projects yang di-lead oleh user ini
		$reviewCards = Card::where('status', 'review')
			->whereHas('board.project.members', function($q) use ($userId) {
				$q->where('user_id', $userId)
				  ->whereIn('role', ['admin', 'teamlead']);
			})
			->with([
				'board.project',
				'assignments.user',
				'subtasks',
				'comments' => function($q) {
					$q->latest()->limit(5);
				},
				'comments.user',
				'creator',
				'timeLogs' => function($q) {
					$q->whereNotNull('end_time');
				}
			])
			->orderBy('created_at', 'desc')
			->get();

		// Hitung total waktu untuk setiap card
		$reviewCards->each(function($card) {
			// Total work time
			$totalMinutes = $card->timeLogs->sum('duration_minutes');
			$card->total_work_time = $this->formatWorkTime($totalMinutes);

			// Submitted time (last comment or updated_at)
			$lastComment = $card->comments->first();
			$card->submitted_time = $lastComment ? $lastComment->created_at : $card->updated_at;

			// Get last assignee comment
			$assigneeIds = $card->assignments->pluck('user_id');
			$card->last_assignee_comment = $card->comments()
				->whereIn('user_id', $assigneeIds)
				->latest()
				->first();
		});

		// Get all projects for filter
		$projects = Project::whereHas('members', function($q) use ($userId) {
			$q->where('user_id', $userId)
			  ->whereIn('role', ['admin', 'teamlead']);
		})->get();

		return view('teamlead.reviw', compact(
			'user',
			'reviewCards',
			'projects'
		));
	}

	private function formatWorkTime($minutes)
	{
		if ($minutes == 0) return '0h 0m';

		$hours = floor($minutes / 60);
		$mins = $minutes % 60;

		return ($hours > 0 ? $hours . 'h ' : '') . ($mins > 0 ? $mins . 'm' : '');
	}

	public function reviewDetail($cardId)
	{
		$card = Card::with([
			'board.project',
			'assignments.user',
			'subtasks',
			'comments.user',
			'creator'
		])->findOrFail($cardId);

		// Format data for JSON response
		return response()->json([
			'id' => $card->id,
			'card_title' => $card->card_title,
			'description' => $card->description,
			'priority' => $card->priority,
			'status' => $card->status,
			'due_date' => $card->due_date ? \Carbon\Carbon::parse($card->due_date)->format('d M Y') : null,
			'assignments' => $card->assignments->map(function($assignment) {
				return [
					'user' => [
						'id' => $assignment->user->id,
						'full_name' => $assignment->user->full_name ?? $assignment->user->username,
						'username' => $assignment->user->username,
						'role' => ucfirst($assignment->user->role)
					]
				];
			}),
			'subtasks' => $card->subtasks->map(function($subtask) {
				return [
					'id' => $subtask->id,
					'subtask_title' => $subtask->subtask_title,
					'status' => $subtask->status
				];
			}),
			'comments' => $card->comments->map(function($comment) {
				return [
					'id' => $comment->id,
					'comment_text' => $comment->comment_text,
					'created_at_human' => $comment->created_at->diffForHumans(),
					'user' => [
						'full_name' => $comment->user->full_name ?? $comment->user->username,
						'username' => $comment->user->username
					]
				];
			})
		]);
	}

    /**
     * Show all projects
     *
     * @return \Illuminate\View\View
     */
    public function projects()
    {
        $user = Auth::user();

        $projects = Project::with(['boards.cards', 'members', 'creator'])
            ->where(function($query) use ($user) {
                $query->where('created_by', $user->id)
                    ->orWhereHas('members', function($q) use ($user) {
                        $q->where('project_members.user_id', $user->id)
                            ->whereIn('role', ['super_admin', 'admin', 'teamlead']);
                    });
            })
            ->latest()
            ->paginate(12);

        return view('teamlead.projects.index', compact('projects'));
    }

    /**
     * Show single project details
     *
     * @param Project $project
     * @return \Illuminate\View\View
     */
    public function showProject(Project $project)
    {
        $user = Auth::user();

        if (!$this->userHasProjectAccess($user, $project)) {
            abort(403, 'Unauthorized access to this project.');
        }

        $project->load([
            'boards.cards.subtasks',
            'boards.cards.assignedMembers',
            'boards.cards.comments.user',
            'members'
        ]);

        $stats = $this->calculateProjectStats($project);

        return view('teamlead.projects.show', compact('project', 'stats'));
    }

    /**
     * Show project details (alias)
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        /** @var Project $project */
        $project = Project::with([
            'boards.cards.subtasks',
            'boards.cards.assignedMembers',
            'boards.cards.comments.user',
            'members'
        ])->findOrFail($id);

        $user = Auth::user();

        if (!$this->userHasProjectAccess($user, $project)) {
            abort(403, 'Unauthorized access to this project.');
        }

        $stats = $this->calculateProjectStats($project);

        return view('teamlead.projects.show', compact('project', 'stats'));
    }

    /**
     * Show reports index
     *
     * @return \Illuminate\View\View
     */
    public function reports()
    {
        $user = Auth::user();

        $projects = Project::with(['boards.cards'])
            ->where(function($query) use ($user) {
                $query->where('created_by', $user->id)
                    ->orWhereHas('members', function($q) use ($user) {
                        $q->where('project_members.user_id', $user->id)
                            ->whereIn('role', ['super_admin', 'admin', 'teamlead']);
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

    /**
     * Show single project report
     *
     * @param Project $project
     * @return \Illuminate\View\View
     */
    public function showReport(Project $project)
    {
        $user = Auth::user();

        if (!$this->userHasProjectAccess($user, $project)) {
            abort(403, 'Unauthorized access to this project.');
        }

        $project->load(['boards.cards.assignedMembers', 'members']);
        $stats = $this->calculateProjectStats($project);
        $teamPerformance = $this->getTeamPerformance($project);

        return view('teamlead.reports.show', compact('project', 'stats', 'teamPerformance'));
    }

    /**
     * Show project details with team performance
     *
     * @param Project $project
     * @return \Illuminate\View\View
     */
    public function projectDetail(Project $project)
    {
        $user = Auth::user();

        if (!$this->userHasProjectAccess($user, $project)) {
            abort(403, 'Unauthorized access to this project.');
        }

        $project->load([
            'boards.cards.assignedMembers',
            'boards.cards.comments.user',
            'members'
        ]);

        $teamPerformance = $this->getTeamPerformance($project);

        return view('teamlead.project-detail', compact('project', 'teamPerformance'));
    }

    /**
     * Show team performance report
     *
     * @return \Illuminate\View\View
     */
    public function teamReport()
    {
        $user = Auth::user();

        $projects = Project::where('created_by', $user->id)
            ->orWhereHas('members', function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->whereIn('role', ['super_admin', 'admin', 'teamlead']);
            })
            ->get();

        $projectIds = $projects->pluck('id');

        // FIXED relationship name from projectMemberships to projectMembers
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

                if (method_exists($member, 'cardAssignments')) {
                    $totalTasks = $member->cardAssignments()->count();
                    $completedTasks = $member->cardAssignments()
                        ->where('assignment_status', 'completed')
                        ->count();
                }

                return [
                    'member' => $member,
                    'total_tasks' => $totalTasks,
                    'completed_tasks' => $completedTasks,
                    'total_hours' => $totalHours,
                    'this_week_hours' => $thisWeekHours,
                ];
            });

        return view('teamlead.team-report', compact('teamMembers'));
    }

    /**
     * Show task assignments overview
     *
     * @return \Illuminate\View\View
     */
    public function taskAssignments()
    {
        $user = Auth::user();

        $projects = Project::where('created_by', $user->id)
            ->orWhereHas('members', function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->whereIn('role', ['super_admin', 'admin', 'teamlead']);
            })
            ->with(['boards.cards.assignedMembers', 'boards.cards.assignments'])
            ->get();

        $cards = $projects->flatMap->boards->flatMap->cards;

        $unassigned = $cards->filter(function($card) {
            return $card->assignedMembers->count() === 0;
        });

        $assigned = $cards->filter(function($card) {
            return $card->assignedMembers->count() > 0;
        });

        return view('teamlead.task-assignments', compact('unassigned', 'assigned'));
    }

    /**
     * Assign task to team member
     *
     * @param Request $request
     * @param Card $card
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignTask(Request $request, Card $card)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        try {
            if (method_exists($card, 'assignUser')) {
                $card->assignUser($validated['user_id']);
            } else {
                $card->assignedMembers()->attach($validated['user_id']);
            }

            return back()->with('success', 'Task assigned successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to assign task: ' . $e->getMessage());
        }
    }

    /**
     * Remove task assignment
     *
     * @param Card $card
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeAssignment(Card $card, User $user)
    {
        try {
            $card->assignedMembers()->detach($user->id);
            return back()->with('success', 'Assignment removed successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to remove assignment: ' . $e->getMessage());
        }
    }

    /**
     * Show messages/chat interface
     *
     * @return \Illuminate\View\View
     */
    // public function messages()
    // {
        // $userId = Auth::id();

        // // Get all team members from user's projects
        // $projectIds = Project::where('created_by', $userId)
            // ->orWhereHas('members', function ($query) use ($userId) {
                // $query->where('user_id', $userId)
                    // ->whereIn('role', ['admin', 'super_admin', 'teamlead']);
            // })
            // ->pluck('id');

        // // Get team members to chat with
        // $teamMembers = User::whereHas('projectMembers', function($query) use ($projectIds) {
                // $query->whereIn('project_id', $projectIds);
            // })
            // ->where('id', '!=', $userId)
            // ->get()
            // ->map(function($member) use ($userId) {
                // // Get last message between users
                // $lastMessage = Message::where(function($query) use ($userId, $member) {
                        // $query->where('sender_id', $userId)
                            // ->where('receiver_id', $member->id);
                    // })
                    // ->orWhere(function($query) use ($userId, $member) {
                        // $query->where('sender_id', $member->id)
                            // ->where('receiver_id', $userId);
                    // })
                    // ->latest()
                    // ->first();

                // // Count unread messages
                // $unreadCount = Message::where('sender_id', $member->id)
                    // ->where('receiver_id', $userId)
                    // ->where('is_read', false)
                    // ->count();

                // $member->last_message = $lastMessage;
                // $member->unread_count = $unreadCount;
                // $member->is_online = $member->updated_at > now()->subMinutes(5);

                // return $member;
            // })
            // ->sortByDesc(function($member) {
                // return $member->last_message ? $member->last_message->created_at : $member->created_at;
            // });

        // return view('teamlead.messages', compact('teamMembers'));
    // }

         public function messages()
    {
        $userId = Auth::id();

        // Ambil daftar user yang pernah chat dengan current user
        $contacts = $this->getContactsList($userId);

        // Jika ada kontak, ambil pesan dari kontak pertama
        $selectedContact = $contacts->first();
        $messages = [];

        if ($selectedContact) {
            $messages = Message::betweenUsers($userId, $selectedContact->id)->get();

            // Mark pesan dari kontak ini sebagai sudah dibaca
            Message::where('sender_id', $selectedContact->id)
                ->where('receiver_id', $userId)
                ->where('is_read', false)
                ->update([
                    'is_read' => true,
                    'read_at' => now()
                ]);
        }

        return view('teamlead.messages', compact('contacts', 'selectedContact', 'messages'));
    }

    /**
     * Load chat dengan user tertentu (AJAX)
     */
    public function loadChat($userId)
    {
        $currentUserId = Auth::id();

        // Ambil data user yang dipilih
        $selectedContact = User::findOrFail($userId);

        // Ambil semua pesan antara current user dan user yang dipilih
        $messages = Message::betweenUsers($currentUserId, $userId)->get();

        // Mark pesan dari user ini sebagai sudah dibaca
        Message::where('sender_id', $userId)
            ->where('receiver_id', $currentUserId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'contact' => [
                'id' => $selectedContact->id,
                'name' => $selectedContact->full_name ?? $selectedContact->username,
                'avatar' => $this->getAvatarUrl($selectedContact),
                'is_online' => $selectedContact->is_online ?? false,
            ],
            'messages' => $messages->map(function ($message) use ($currentUserId) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'message' => $message->message,
                    'is_mine' => $message->sender_id == $currentUserId,
                    'created_at' => $message->created_at->format('H:i'),
                    'sender_name' => $message->sender->full_name ?? $message->sender->username,
                    'sender_avatar' => $this->getAvatarUrl($message->sender),
                ];
            })
        ]);
    }

    /**
     * Kirim pesan baru (AJAX)
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:5000',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'message' => $message->message,
                'is_mine' => true,
                'created_at' => $message->created_at->format('H:i'),
                'sender_name' => Auth::user()->full_name ?? Auth::user()->username,
                'sender_avatar' => $this->getAvatarUrl(Auth::user()),
            ]
        ]);
    }

    /**
     * Get daftar kontak (users yang pernah chat)
     */
    // private function getContactsList($userId)
    // {
        // // Subquery untuk mendapatkan pesan terakhir
        // $latestMessages = Message::select('id', 'sender_id', 'receiver_id', 'message', 'created_at')
            // ->where(function ($query) use ($userId) {
                // $query->where('sender_id', $userId)
                      // ->orWhere('receiver_id', $userId);
            // })
            // ->orderBy('created_at', 'desc');

        // // Ambil daftar user yang pernah chat
        // $contacts = User::select('users.id' , 'users.username' , 'users.email' , 'users.password' , 'users.full_name' , 'users.role' , 'users.current_task_status')
            // ->joinSub($latestMessages, 'latest_messages', function ($join) use ($userId) {
                // $join->on(function ($query) use ($userId) {
                    // $query->where('users.id', '=', 'latest_messages.sender_id')
                          // ->where('latest_messages.receiver_id', '=', $userId);
                // })->orOn(function ($query) use ($userId) {
                    // $query->where('users.id', '=', 'latest_messages.receiver_id')
                          // ->where('latest_messages.sender_id', '=', $userId);
                // });
            // })
            // ->where('users.id', '!=', $userId)
            // //->groupBy('users.id')
             // ->groupBy('users.id' , 'users.username' , 'users.email' , 'users.password' , 'users.full_name' , 'users.role' , 'users.current_task_status')
            // ->get()
            // ->map(function ($user) use ($userId) {
                // // Ambil pesan terakhir
                // $lastMessage = Message::where(function ($q) use ($userId, $user) {
                    // $q->where('sender_id', $userId)->where('receiver_id', $user->id);
                // })->orWhere(function ($q) use ($userId, $user) {
                    // $q->where('sender_id', $user->id)->where('receiver_id', $userId);
                // })->latest()->first();

                // // Hitung unread messages
                // $unreadCount = Message::where('sender_id', $user->id)
                    // ->where('receiver_id', $userId)
                    // ->where('is_read', false)
                    // ->count();

                // $user->last_message = $lastMessage;
                // $user->unread_count = $unreadCount;

                // return $user;
            // })
            // ->sortByDesc(function ($user) {
                // return $user->last_message ? $user->last_message->created_at : null;
            // });

        // return $contacts;
    // }

	private function getContactsList($userId)
	{
		// Ambil semua user kecuali diri sendiri
		$users = User::where('id', '!=', $userId)->get();

		// Map: tambahkan last_message + unread_count
		$contacts = $users->map(function ($user) use ($userId) {

			// Ambil pesan terakhir antara kedua user
			$lastMessage = Message::where(function ($q) use ($userId, $user) {
					$q->where('sender_id', $userId)
					  ->where('receiver_id', $user->id);
				})
				->orWhere(function ($q) use ($userId, $user) {
					$q->where('sender_id', $user->id)
					  ->where('receiver_id', $userId);
				})
				->latest('created_at')
				->first();

			// Hitung pesan belum dibaca
			$unreadCount = Message::where('sender_id', $user->id)
				->where('receiver_id', $userId)
				->where('is_read', false)
				->count();

			$user->last_message = $lastMessage;
			$user->unread_count = $unreadCount;

			return $user;
		});

		// Urutkan berdasarkan last_message terbaru
		return $contacts->sortByDesc(function ($user) {
			return $user->last_message->created_at ?? null;
		})->values();
	}

    /**
     * Generate avatar URL
     */
    private function getAvatarUrl($user)
    {
        $name = $user->full_name ?? $user->username;
        return "https://ui-avatars.com/api/?name=" . urlencode($name) . "&background=2563EB&color=fff";
    }

    /**
     * Get unread count untuk badge (AJAX)
     */
    public function getUnreadCount()
    {
        $unreadCount = Message::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json([
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Cari kontak untuk New Chat
     */
    public function searchUsers(Request $request)
    {
        $search = $request->get('search', '');
        $userId = Auth::id();

        $users = User::where('id', '!=', $userId)
            ->where(function ($query) use ($search) {
                $query->where('username', 'like', "%{$search}%")
                      ->orWhere('full_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->limit(20)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->full_name ?? $user->username,
                    'email' => $user->email,
                    'avatar' => $this->getAvatarUrl($user),
                ];
            });

        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }

    /**
     * Get conversation with specific user
     *
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConversation($userId)
    {
        $currentUserId = Auth::id();

        // Get messages between current user and target user
        $messages = Message::where(function($query) use ($currentUserId, $userId) {
                $query->where('sender_id', $currentUserId)
                    ->where('receiver_id', $userId);
            })
            ->orWhere(function($query) use ($currentUserId, $userId) {
                $query->where('sender_id', $userId)
                    ->where('receiver_id', $currentUserId);
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        Message::where('sender_id', $userId)
            ->where('receiver_id', $currentUserId)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }

    /**
     * Send message to user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    // public function sendMessage(Request $request)
    // {
    //     $validated = $request->validate([
    //         'receiver_id' => 'required|exists:users,id',
    //         'message' => 'required|string|max:1000',
    //     ]);

    //     try {
    //         $message = Message::create([
    //             'sender_id' => Auth::id(),
    //             'receiver_id' => $validated['receiver_id'],
    //             'message' => $validated['message'],
    //             'is_read' => false,
    //         ]);

    //         $message->load(['sender', 'receiver']);

    //         return response()->json([
    //             'success' => true,
    //             'message' => $message
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'error' => 'Failed to send message: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    /**
     * Mark message as read
     *
     * @param int $messageId
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead($messageId)
    {
        try {
            $message = Message::where('id', $messageId)
                ->where('receiver_id', Auth::id())
                ->firstOrFail();

            $message->update([
                'is_read' => true,
                'read_at' => now()
            ]);

            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to mark as read'
            ], 500);
        }
    }

    /**
     * Get unread messages count
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function getUnreadCount()
    // {
    //     $count = Message::where('receiver_id', Auth::id())
    //         ->where('is_read', false)
    //         ->count();

    //     return response()->json([
    //         'success' => true,
    //         'count' => $count
    //     ]);
    // }

    /**
     * Search users to chat with
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    // public function searchUsers(Request $request)
    // {
    //     $query = $request->input('query');
    //     $userId = Auth::id();

    //     $projectIds = Project::where('created_by', $userId)
    //         ->orWhereHas('members', function ($q) use ($userId) {
    //             $q->where('user_id', $userId)
    //                 ->whereIn('role', ['admin', 'super_admin', 'teamlead']);
    //         })
    //         ->pluck('id');

    //     $users = User::whereHas('projectMembers', function($q) use ($projectIds) {
    //             $q->whereIn('project_id', $projectIds);
    //         })
    //         ->where('id', '!=', $userId)
    //         ->where(function($q) use ($query) {
    //             $q->where('name', 'LIKE', "%{$query}%")
    //               ->orWhere('email', 'LIKE', "%{$query}%");
    //         })
    //         ->limit(10)
    //         ->get(['id', 'name', 'email']);

    //     return response()->json([
    //         'success' => true,
    //         'users' => $users
    //     ]);
    // }

    /**
     * Calculate project statistics
     *
     * @param Project $project
     * @return array
     */
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

    /**
     * Get team performance data for a project
     *
     * @param Project $project
     * @return \Illuminate\Support\Collection
     */
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

    /**
     * Check if user has access to project
     *
     * @param User $user
     * @param Project $project
     * @return bool
     */
    protected function userHasProjectAccess(User $user, Project $project)
    {
        if ($project->created_by === $user->id) {
            return true;
        }

        $member = $project->members()
            ->where('user_id', $user->id)
            ->whereIn('role', ['super_admin', 'admin', 'teamlead'])
            ->first();

        return $member !== null;
    }

    //     public function riwayat()
    // {
    //     $userId = Auth::id();
    //     $user = Auth::user();

    //     // Get all projects where user is team lead
    //     $projects = Project::where('created_by', $userId)
    //         ->orWhereHas('members', function ($query) use ($userId) {
    //             $query->where('user_id', $userId)
    //                 ->whereIn('role', ['admin', 'super_admin', 'teamlead']);
    //         })
    //         ->with(['boards.cards.subtasks', 'boards.cards.assignedMembers', 'members'])
    //         ->get();

    //     // Calculate progress for each project
    //     $projects->each(function ($project) {
    //         $totalCards = $project->boards->sum(fn($b) => $b->cards->count());
    //         $doneCards = $project->boards->sum(fn($b) => $b->cards->where('status', 'done')->count());
    //         $project->progress = $totalCards > 0 ? round(($doneCards / $totalCards) * 100) : 0;
    //         $project->total_cards = $totalCards;
    //         $project->done_cards = $doneCards;

    //         if ($project->deadline) {
    //             $daysLeft = now()->diffInDays($project->deadline, false);
    //             $project->days_left = (int)$daysLeft;
    //             $project->deadline_status = $daysLeft > 0 ? 'active' : 'overdue';
    //         } else {
    //             $project->days_left = null;
    //             $project->deadline_status = null;
    //         }
    //     });

    //     // Get all team members from all projects - FIXED relationship name
    //     $projectIds = $projects->pluck('id');

    //     $teamMembers = User::whereHas('projectMembers', function($query) use ($projectIds) {
    //         $query->whereIn('project_id', $projectIds);
    //     })->with(['assignedCards' => function($query) {
    //         $query->whereIn('status', ['todo', 'in_progress', 'review']);
    //     }])->get();

    //     // Add current task info to team members
    //     foreach($teamMembers as $member) {
    //         $member->current_task = $member->assignedCards->first();
    //         $member->active_tasks_count = $member->assignedCards->count();
    //     }

    //     // Get recent comments
    //     $recentComments = Comment::whereHas('card.board.project', function ($query) use ($projectIds) {
    //         $query->whereIn('projects.id', $projectIds);
    //     })
    //         ->with(['user', 'card.board.project'])
    //         ->latest()
    //         ->take(10)
    //         ->get();

    //     // Calculate statistics
    //     $totalTasks = $projects->sum('total_cards');
    //     $completedTasks = $projects->sum('done_cards');
    //     $todoTasks = $projects->sum(fn($p) => $p->boards->sum(fn($b) => $b->cards->where('status', 'todo')->count()));
    //     $inProgressTasks = $projects->sum(fn($p) => $p->boards->sum(fn($b) => $b->cards->where('status', 'in_progress')->count()));
    //     $reviewTasks = $projects->sum(fn($p) => $p->boards->sum(fn($b) => $b->cards->where('status', 'review')->count()));
    //     $doneTasks = $completedTasks;

    //     return view('teamlead.history', compact(
    //         'projects',
    //         'teamMembers',
    //         'totalTasks',
    //         'completedTasks',
    //         'todoTasks',
    //         'inProgressTasks',
    //         'reviewTasks',
    //         'doneTasks',
    //         'recentComments'
    //     ));
    // }

public function riwayat()
{
    $userId = Auth::id();
    $user = Auth::user();

    // Ambil semua comment yang memiliki status dalam format [STATUS]
    $historyComments = Comment::where(function($query) {
            $query->where('comment_text', 'LIKE', '%[APPROVED]%')
                  ->orWhere('comment_text', 'LIKE', '%[REJECTED]%')
                  ->orWhere('comment_text', 'LIKE', '%[COMPLETED]%')
                  ->orWhere('comment_text', 'LIKE', '%[CANCELLED]%');
        })
        ->with(['card.board.project', 'user'])
        ->orderBy('created_at', 'desc')
        ->get();

    // Filter berdasarkan project yang user terlibat
    $userProjects = $user->projects->pluck('id');
    
    $historyComments = $historyComments->filter(function($comment) use ($userProjects) {
        return $comment->card && 
               $comment->card->board && 
               $comment->card->board->project && 
               $userProjects->contains($comment->card->board->project->id);
    });

    // Extract status dari comment
    $historyComments = $historyComments->map(function($comment) {
        // Cek status berdasarkan text
        if (stripos($comment->comment_text, '[APPROVED]') !== false) {
            $comment->status = 'APPROVED';
        } elseif (stripos($comment->comment_text, '[REJECTED]') !== false) {
            $comment->status = 'REJECTED';
        } elseif (stripos($comment->comment_text, '[COMPLETED]') !== false) {
            $comment->status = 'COMPLETED';
        } elseif (stripos($comment->comment_text, '[CANCELLED]') !== false) {
            $comment->status = 'CANCELLED';
        } else {
            $comment->status = 'UNKNOWN';
        }
        
        // Bersihkan content dari tag status
        $comment->clean_content = preg_replace('/\[(APPROVED|REJECTED|COMPLETED|CANCELLED)\]\s*/i', '', $comment->comment_text);
        
        return $comment;
    });

    // Hitung statistik
    $stats = [
        'total' => $historyComments->count(),
        'completed' => $historyComments->where('status', 'COMPLETED')->count(),
        'approved' => $historyComments->where('status', 'APPROVED')->count(),
        'rejected' => $historyComments->where('status', 'REJECTED')->count(),
        'cancelled' => $historyComments->where('status', 'CANCELLED')->count(),
    ];

    // Get unique projects for filter
    $projects = $user->projects;

    return view('teamlead.history', compact(
        'historyComments',
        'stats',
        'projects',
        'user'
    ));
}

public function getCardDetail($cardId)
{
    $card = Card::with(['board.project', 'assignedUser', 'comments.user'])
        ->findOrFail($cardId);

    // Get the latest status from comments
    $latestStatusComment = $card->comments()
        ->where(function($query) {
            $query->where('comment_text', 'LIKE', '%[APPROVED]%')
                  ->orWhere('comment_text', 'LIKE', '%[REJECTED]%')
                  ->orWhere('comment_text', 'LIKE', '%[COMPLETED]%')
                  ->orWhere('comment_text', 'LIKE', '%[CANCELLED]%');
        })
        ->latest()
        ->first();

    $status = 'UNKNOWN';
    if ($latestStatusComment) {
        if (stripos($latestStatusComment->comment_text, '[APPROVED]') !== false) {
            $status = 'APPROVED';
        } elseif (stripos($latestStatusComment->comment_text, '[REJECTED]') !== false) {
            $status = 'REJECTED';
        } elseif (stripos($latestStatusComment->comment_text, '[COMPLETED]') !== false) {
            $status = 'COMPLETED';
        } elseif (stripos($latestStatusComment->comment_text, '[CANCELLED]') !== false) {
            $status = 'CANCELLED';
        }
    }

    return response()->json([
        'title' => $card->title,
        'description' => $card->description,
        'assignee' => $card->assignedUser ? ($card->assignedUser->full_name ?: $card->assignedUser->username) : 'Not assigned',
        'project' => $card->board->project->project_name,
        'board' => $card->board->board_name,
        'card_status' => ucfirst($card->status),
        'status' => $status,
        'due_date' => $card->due_date ? \Carbon\Carbon::parse($card->due_date)->format('d M Y') : null,
        'comments' => $card->comments->map(function($comment) {
            return [
                'user' => $comment->user->full_name ?: $comment->user->username,
                'content' => $comment->comment_text,
                'time' => $comment->created_at->diffForHumans(),
            ];
        })
    ]);
}

public function exportHistory()
{
    $userId = Auth::id();
    $user = Auth::user();

    $historyComments = Comment::where(function($query) {
            $query->where('comment_text', 'LIKE', '%[APPROVED]%')
                  ->orWhere('comment_text', 'LIKE', '%[REJECTED]%')
                  ->orWhere('comment_text', 'LIKE', '%[COMPLETED]%')
                  ->orWhere('comment_text', 'LIKE', '%[CANCELLED]%');
        })
        ->with(['card.board.project', 'user'])
        ->orderBy('created_at', 'desc')
        ->get();

    $userProjects = $user->projects->pluck('id');
    
    $historyComments = $historyComments->filter(function($comment) use ($userProjects) {
        return $comment->card && 
               $comment->card->board && 
               $comment->card->board->project && 
               $userProjects->contains($comment->card->board->project->id);
    });

    // Create CSV
    $filename = 'task_history_' . date('Y-m-d_H-i-s') . '.csv';
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ];

    $callback = function() use ($historyComments) {
        $file = fopen('php://output', 'w');
        
        // Header
        fputcsv($file, ['Date', 'Status', 'Task', 'Description', 'User', 'Project', 'Board']);
        
        // Data
        foreach ($historyComments as $comment) {
            // Tentukan status
            $status = 'UNKNOWN';
            if (stripos($comment->comment_text, '[APPROVED]') !== false) {
                $status = 'APPROVED';
            } elseif (stripos($comment->comment_text, '[REJECTED]') !== false) {
                $status = 'REJECTED';
            } elseif (stripos($comment->comment_text, '[COMPLETED]') !== false) {
                $status = 'COMPLETED';
            } elseif (stripos($comment->comment_text, '[CANCELLED]') !== false) {
                $status = 'CANCELLED';
            }
            
            $cleanContent = preg_replace('/\[(APPROVED|REJECTED|COMPLETED|CANCELLED)\]\s*/i', '', $comment->comment_text);
            
            fputcsv($file, [
                $comment->created_at->format('Y-m-d H:i:s'),
                $status,
                $comment->card->title,
                $cleanContent,
                $comment->user->full_name ?: $comment->user->username,
                $comment->card->board->project->project_name,
                $comment->card->board->board_name,
            ]);
        }
        
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

}

