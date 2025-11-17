<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $projects = Project::with(['members', 'boards.cards'])->get();

        $totalProjects = $projects->count();

        $activeTasks = $projects->sum(function($project) {
            return $project->boards->sum(function($board) {
                return $board->cards->whereIn('status', ['todo', 'in_progress', 'review'])->count();
            });
        });

        $teamMembers = $projects->pluck('members')->flatten()->unique('id')->count();

        $allTasks = $projects->sum(function($project) {
            return $project->boards->sum(function($board) {
                return $board->cards->count();
            });
        });

        $completedTasks = $projects->sum(function($project) {
            return $project->boards->sum(function($board) {
                return $board->cards->where('status', 'done')->count();
            });
        });

        $completionRate = $allTasks > 0 ? round(($completedTasks / $allTasks) * 100) : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'total_projects' => $totalProjects,
                    'active_tasks' => $activeTasks,
                    'team_members' => $teamMembers,
                    'completion_rate' => $completionRate,
                ],
                'projects' => $projects->map(function($project) {
                    $totalCards = $project->boards->sum(fn($b) => $b->cards->count());
                    $doneCards = $project->boards->sum(fn($b) => $b->cards->where('status', 'done')->count());
                    $progress = $totalCards > 0 ? round(($doneCards / $totalCards) * 100) : 0;

                    return [
                        'id' => $project->id,
                        'project_name' => $project->project_name,
                        'description' => $project->description,
                        'thumbnail' => $project->thumbnail ? asset('storage/' . $project->thumbnail) : null,
                        'deadline' => $project->deadline,
                        'boards_count' => $project->boards->count(),
                        'tasks_count' => $totalCards,
                        'progress' => $progress,
                        'members' => $project->members->map(fn($m) => [
                            'id' => $m->id,
                            'full_name' => $m->full_name ?: $m->username,
                            'email' => $m->email,
                            'role' => $m->role,
                        ]),
                    ];
                }),
            ]
        ]);
    }
}
