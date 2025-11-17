<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Models\Card;
use App\Models\Board;
use Illuminate\Support\Carbon;

class MonitoringController extends Controller
{
    public function index()
    {
        try {
            // Load projects dengan relationships yang diperlukan
            $projects = Project::with(['members.user', 'creator', 'boards.cards'])->get();

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

            return view('admin.monitoring.index', compact(
                'projects',
                'projectStats',  // ✅ GUNAKAN projectStats
                'workingUsers',
                'idleUsers',
                'memberDistribution'
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

            return view('admin.monitoring.index', compact(
                'projects',
                'projectStats',
                'workingUsers',
                'idleUsers',
                'memberDistribution'
            ));
        }
    }


    public function show(Project $project)
    {
        try {
            // Load relationships yang diperlukan
            $project->load([
                'members',
                'teamLeads',
                'creator',
                'boards.cards.assignedMembers',
                'boards.cards.subtasks',
                'boards.cards.comments'
            ]);

            // 🔥 GET PROJECT LEADER (Admin dari project_members)
            $projectLeader = $project->members()
                ->wherePivot('role', 'admin')
                ->orWherePivot('role', 'super_admin')
                ->first();

            // Jika tidak ada, gunakan creator
            if (!$projectLeader) {
                $projectLeader = $project->creator;
            }

            // 📊 HITUNG PROJECT STATISTICS
            $totalCards = $project->boards->flatMap->cards->count();
            $completedCards = $project->boards->flatMap->cards->where('status', 'done')->count();
            $inProgressCards = $project->boards->flatMap->cards->where('status', 'in_progress')->count();
            $todoCards = $project->boards->flatMap->cards->where('status', 'todo')->count();
            $reviewCards = $project->boards->flatMap->cards->where('status', 'review')->count();

            $projectStats = [
                'total_tasks' => $totalCards,
                'completed_tasks' => $completedCards,
                'in_progress_tasks' => $inProgressCards,
                'todo_tasks' => $todoCards,
                'review_tasks' => $reviewCards,
                'active_members' => $project->members->count(),
                'progress_percentage' => $totalCards > 0
                    ? round(($completedCards / $totalCards) * 100)
                    : 0,
                'total_boards' => $project->boards->count(),
            ];

            // 👥 TEAM MEMBERS DENGAN STATUS
            $teamMembers = $project->members->map(function($member) use ($project) {
                // Get assignments untuk member ini di project ini
                $assignments = $member->cardAssignments()
                    ->whereHas('card.board', function($query) use ($project) {
                        $query->where('project_id', $project->id);
                    })
                    ->get();

                $totalAssignments = $assignments->count();
                $completedAssignments = $assignments->where('assignment_status', 'completed')->count();
                $inProgressAssignments = $assignments->where('assignment_status', 'in_progress')->count();

                // Get current task jika sedang working
                $currentTask = null;
                if ($member->current_task_status === 'working') {
                    $activeTimeLog = $member->timeLogs()
                        ->whereNull('end_time')
                        ->with('card')
                        ->first();
                    $currentTask = $activeTimeLog ? $activeTimeLog->card : null;
                }

                // Get role dari pivot
                $memberRole = $project->members()
                    ->where('users.id', $member->id)
                    ->first()
                    ->pivot->role ?? 'member';

                return [
                    'member' => $member,
                    'role' => $memberRole,
                    'total_tasks' => $totalAssignments,
                    'completed_tasks' => $completedAssignments,
                    'in_progress_tasks' => $inProgressAssignments,
                    'completion_rate' => $totalAssignments > 0
                        ? round(($completedAssignments / $totalAssignments) * 100)
                        : 0,
                    'current_task' => $currentTask,
                    'status' => $member->current_task_status,
                ];
            });

            // 🎯 HIGH PRIORITY TASKS
            $highPriorityTasks = $project->boards
                ->flatMap->cards
                ->where('priority', 'high')
                ->whereIn('status', ['todo', 'in_progress', 'review'])
                ->sortBy('due_date')
                ->take(10);

            // ⏰ OVERDUE TASKS
            $overdueTasks = $project->boards
                ->flatMap->cards
                ->filter(function($card) {
                    return $card->due_date &&
                           $card->due_date->isPast() &&
                           $card->status !== 'done';
                })
                ->sortBy('due_date')
                ->take(10);

            // 📈 BOARD STATISTICS
            $boardStats = $project->boards->map(function($board) {
                $totalCards = $board->cards->count();
                $doneCards = $board->cards->where('status', 'done')->count();

                return [
                    'board' => $board,
                    'total_cards' => $totalCards,
                    'done_cards' => $doneCards,
                    'progress' => $totalCards > 0 ? round(($doneCards / $totalCards) * 100) : 0,
                    'todo' => $board->cards->where('status', 'todo')->count(),
                    'in_progress' => $board->cards->where('status', 'in_progress')->count(),
                    'review' => $board->cards->where('status', 'review')->count(),
                    'done' => $doneCards,
                ];
            });

            // 💬 RECENT ACTIVITY (Comments)
            $recentActivity = $project->boards
                ->flatMap->cards
                ->flatMap->comments
                ->sortByDesc('created_at')
                ->take(10);

            // ⏱️ TIME TRACKING SUMMARY
            $totalEstimatedHours = $project->boards->flatMap->cards->sum('estimated_hours');
            $totalActualHours = $project->boards->flatMap->cards->sum('actual_hours');
            $hoursRemaining = max(0, $totalEstimatedHours - $totalActualHours);

            $timeTracking = [
                'estimated' => $totalEstimatedHours,
                'actual' => $totalActualHours,
                'remaining' => $hoursRemaining,
                'percentage' => $totalEstimatedHours > 0
                    ? round(($totalActualHours / $totalEstimatedHours) * 100)
                    : 0,
            ];

        } catch (\Exception $e) {
            // Fallback dengan default values
            \Log::error('Error in MonitoringController show method: ' . $e->getMessage());

            $projectLeader = $project->creator ?? null;
            $projectStats = [
                'total_tasks' => 0,
                'completed_tasks' => 0,
                'in_progress_tasks' => 0,
                'todo_tasks' => 0,
                'review_tasks' => 0,
                'active_members' => 0,
                'progress_percentage' => 0,
                'total_boards' => 0,
            ];
            $teamMembers = collect();
            $highPriorityTasks = collect();
            $overdueTasks = collect();
            $boardStats = collect();
            $recentActivity = collect();
            $timeTracking = [
                'estimated' => 0,
                'actual' => 0,
                'remaining' => 0,
                'percentage' => 0,
            ];
        }

        return view('admin.monitoring.show', compact(
            'project',
            'projectLeader',
            'projectStats',
            'teamMembers',
            'highPriorityTasks',
            'overdueTasks',
            'boardStats',
            'recentActivity',
            'timeTracking'
        ));
    }
}
    // public function show2(Project $project)
    // {
    //     ✅ INISIALISASI DEFAULT DULU
    //     $projectStats = [
    //         'total_tasks' => 0,
    //         'completed_tasks' => 0,
    //         'in_progress_tasks' => 0,
    //         'active_members' => 0,
    //         'progress_percentage' => 0
    //     ];

    //     try {
    //         // Load relationships yang diperlukan
    //         $project->load(['members.user', 'creator', 'boards.cards']);

    //         // Hitung total anggota aktif
    //         $projectStats['active_members'] = $project->members->count();

    //         // Hitung tasks dari boards dan cards
    //         $totalCards = $project->boards->flatMap->cards->count();
    //         $completedCards = $project->boards->flatMap->cards->where('status', 'done')->count();
    //         $inProgressCards = $project->boards->flatMap->cards->where('status', 'in_progress')->count();

    //         $projectStats['total_tasks'] = $totalCards;
    //         $projectStats['completed_tasks'] = $completedCards;
    //         $projectStats['in_progress_tasks'] = $inProgressCards;

    //         // Hitung progress percentage
    //         $projectStats['progress_percentage'] = $totalCards > 0
    //             ? round(($completedCards / $totalCards) * 100)
    //             : 0;

    //         // Coba load leader jika ada relationship
    //         if (method_exists($project, 'leader') && $project->leader_id) {
    //             $project->load('leader');
    //         }

    //         // Jika tidak ada boards, buat default boards untuk project ini
    //         if ($project->boards->count() === 0) {
    //             $defaultBoards = [
    //                 ['board_name' => 'To Do', 'position' => 1],
    //                 ['board_name' => 'In Progress', 'position' => 2],
    //                 ['board_name' => 'Review', 'position' => 3],
    //                 ['board_name' => 'Done', 'position' => 4],
    //             ];

    //             foreach ($defaultBoards as $boardData) {
    //                 Board::create([
    //                     'project_id' => $project->id,
    //                     'board_name' => $boardData['board_name'],
    //                     'position' => $boardData['position'],
    //                     'created_by' => auth()->id() ?? 1
    //                 ]);
    //             }

    //             // Reload project dengan boards yang baru dibuat
    //             $project->load('boards');
    //         }

    //     } catch (\Exception $e) {
    //         // Tetap lanjut dengan default values
    //         // Bisa log error jika perlu
    //         \Log::error('Error in MonitoringController show method: ' . $e->getMessage());
    //     }

    //     return view('admin.monitoring.show', compact('project', 'projectStats'));
    // }
