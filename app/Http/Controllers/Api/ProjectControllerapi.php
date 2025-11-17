<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectControllerapi extends Controller
{
    /**
     * Get Dashboard Stats
     * GET /api/dashboard
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();

        // Get projects where user is member or owner
        $projects = Project::whereHas('members', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orWhere('owner_id', $user->id)->get();

        $stats = [
            'total_projects' => $projects->count(),
            'total_tasks' => 0,
            'completed_tasks' => 0,
            'in_progress_tasks' => 0,
        ];

        foreach ($projects as $project) {
            $stats['total_tasks'] += $project->cards()->count();
            $stats['completed_tasks'] += $project->cards()->where('status', 'done')->count();
            $stats['in_progress_tasks'] += $project->cards()->where('status', 'in_progress')->count();
        }

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'recent_projects' => $projects->take(5),
        ]);
    }

    /**
     * Get All Projects
     * GET /api/projects
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $projects = Project::with(['boards', 'cards', 'members'])
            ->whereHas('members', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orWhere('owner_id', $user->id)
            ->get()
            ->map(function($project) {
                return [
                    'id' => $project->id,
                    'project_name' => $project->project_name,
                    'description' => $project->description,
                    'progress' => $project->progress ?? 0,
                    'total_tasks' => $project->cards->count(),
                    'done_count' => $project->cards->where('status', 'done')->count(),
                ];
            });

        return response()->json([
            'success' => true,
            'projects' => $projects,
        ]);
    }

    /**
     * Create New Project
     * POST /api/projects
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();

            // Create project
            $project = Project::create([
                'project_name' => $request->project_name,
                'description' => $request->description,
                'owner_id' => $user->id,
                'progress' => 0,
            ]);

            // Create default boards
            $defaultBoards = [
                ['board_name' => 'To Do', 'position' => 1],
                ['board_name' => 'In Progress', 'position' => 2],
                ['board_name' => 'Review', 'position' => 3],
                ['board_name' => 'Done', 'position' => 4],
            ];

            foreach ($defaultBoards as $boardData) {
                Board::create([
                    'project_id' => $project->id,
                    'board_name' => $boardData['board_name'],
                    'position' => $boardData['position'],
                ]);
            }

            // Add creator as project member
            $project->members()->attach($user->id);

            return response()->json([
                'success' => true,
                'message' => 'Project created successfully',
                'project' => [
                    'id' => $project->id,
                    'project_name' => $project->project_name,
                    'description' => $project->description,
                    'progress' => $project->progress,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create project: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete Project
     * DELETE /api/projects/{id}
     */
    public function destroy($id)
    {
        try {
            $project = Project::findOrFail($id);

            // Check if user is owner or admin
            if ($project->owner_id !== auth()->id() && auth()->user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this project',
                ], 403);
            }

            $project->delete();

            return response()->json([
                'success' => true,
                'message' => 'Project deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete project: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get Project Board (Kanban)
     * GET /api/projects/{id}/board
     */
    public function getBoard($id)
    {
        try {
            $project = Project::with(['boards.cards.comments'])->findOrFail($id);

            $boards = [];
            foreach ($project->boards as $board) {
                $status = strtolower(str_replace(' ', '_', $board->board_name));
                $boards[$status] = $board->cards->map(function($card) {
                    return [
                        'id' => $card->id,
                        'card_title' => $card->card_title,
                        'description' => $card->description,
                        'priority' => $card->priority,
                        'is_blocker' => $card->is_blocker ?? false,
                        'total_time' => $card->total_time ?? '0h',
                        'status' => $card->status,
                        'comments' => $card->comments->map(function($comment) {
                            return [
                                'id' => $comment->id,
                                'comment_text' => $comment->comment_text,
                                'created_at' => $comment->created_at->format('Y-m-d H:i:s'),
                            ];
                        }),
                    ];
                });
            }

            return response()->json([
                'success' => true,
                'boards' => $boards,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load board: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ... other methods (changeStatus, addComment, etc.)
}
