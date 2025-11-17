<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Card;
use App\Models\Comment;
use App\Models\TimeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Get All Projects
     * GET /api/projects
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            
            // Query database projects + relationships
            $projects = $user->projects()
                ->with(['boards.cards'])
                ->get()
                ->map(function($project) {
                    // Calculate stats
                    $totalCards = $project->boards->sum(fn($b) => $b->cards->count());
                    $doneCards = $project->boards->sum(fn($b) => $b->cards->where('status', 'done')->count());
                    $todoCards = $project->boards->sum(fn($b) => $b->cards->where('status', 'todo')->count());
                    $inProgressCards = $project->boards->sum(fn($b) => $b->cards->where('status', 'in_progress')->count());
                    $reviewCards = $project->boards->sum(fn($b) => $b->cards->where('status', 'review')->count());
                    
                    return [
                        'id' => $project->id,
                        'project_name' => $project->project_name,
                        'description' => $project->description,
                        'deadline' => $project->deadline,
                        'created_at' => $project->created_at->toISOString(),
                        'progress' => $totalCards > 0 ? round(($doneCards / $totalCards) * 100) : 0,
                        'boards_count' => $project->boards->count(),
                        'total_tasks' => $totalCards,
                        'todo_count' => $todoCards,
                        'in_progress_count' => $inProgressCards,
                        'review_count' => $reviewCards,
                        'done_count' => $doneCards,
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => $projects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load projects: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get Project Board (Kanban)
     * GET /api/projects/{id}/board
     */
    public function getBoard(Request $request, $projectId)
    {
        try {
            // Query database
            $project = Project::with(['boards.cards.comments.user', 'boards.cards.assignments.user'])
                ->findOrFail($projectId);
            
            // Check access
            if (!$project->members->contains($request->user()->id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }
            
            // Group cards by status
            $boards = [
                'todo' => [],
                'in_progress' => [],
                'review' => [],
                'done' => [],
                'blocker' => [],
            ];
            
            foreach ($project->boards as $board) {
                foreach ($board->cards as $card) {
                    // Calculate time logs
                    $timeLogs = TimeLog::where('card_id', $card->id)
                        ->whereNotNull('end_time')
                        ->with('user')
                        ->get();
                    
                    $totalMinutes = $timeLogs->sum('duration_minutes');
                    $devMinutes = $timeLogs->where('user.role', 'developer')->sum('duration_minutes');
                    $designerMinutes = $timeLogs->where('user.role', 'designer')->sum('duration_minutes');
                    
                    $cardData = [
                        'id' => $card->id,
                        'card_title' => $card->card_title,
                        'description' => $card->description,
                        'status' => $card->status,
                        'priority' => $card->priority,
                        'due_date' => $card->due_date,
                        'total_time' => $this->formatTime($totalMinutes),
                        'dev_time' => $this->formatTime($devMinutes),
                        'designer_time' => $this->formatTime($designerMinutes),
                        'assigned_members' => $card->assignments->map(fn($a) => [
                            'id' => $a->user->id,
                            'name' => $a->user->full_name ?? $a->user->username,
                            'role' => $a->user->role,
                        ]),
                        'comments' => $card->comments->map(fn($c) => [
                            'id' => $c->id,
                            'username' => $c->user->username,
                            'comment_text' => $c->comment_text,
                            'created_at' => $c->created_at->toISOString(),
                        ]),
                    ];
                    
                    $boards[$card->status][] = $cardData;
                }
            }
            
            // Combine review and blocker for team lead
            $combinedBoards = array_merge(
                $boards['review'] ?? [],
                $boards['blocker'] ?? []
            );
            
            return response()->json([
                'success' => true,
                'data' => [
                    'project' => [
                        'id' => $project->id,
                        'project_name' => $project->project_name,
                        'description' => $project->description,
                    ],
                    'boards' => $boards,
                    'combinedBoards' => $combinedBoards,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load board: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Change Card Status
     * POST /api/cards/{id}/status
     */
    public function changeStatus(Request $request, $cardId)
    {
        $request->validate([
            'status' => 'required|in:todo,in_progress,review,done,blocker'
        ]);
        
        try {
            $card = Card::findOrFail($cardId);
            
            DB::beginTransaction();
            
            // Stop time logs if moving from review to todo
            if ($card->status == 'review' && $request->status == 'todo') {
                $activeLogs = TimeLog::where('card_id', $cardId)
                    ->whereNull('end_time')
                    ->get();
                
                foreach ($activeLogs as $log) {
                    $startTime = \Carbon\Carbon::parse($log->start_time);
                    $endTime = now();
                    $durationMinutes = $startTime->diffInMinutes($endTime);
                    
                    $log->update([
                        'end_time' => $endTime,
                        'duration_minutes' => $durationMinutes
                    ]);
                }
            }
            
            // Update status
            $card->update(['status' => $request->status]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diubah',
                'data' => [
                    'id' => $card->id,
                    'status' => $card->status,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to change status: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Add Comment to Card
     * POST /api/cards/{id}/comment
     */
    public function addComment(Request $request, $cardId)
    {
        $request->validate([
            'comment_text' => 'required|string|max:1000'
        ]);
        
        try {
            $card = Card::findOrFail($cardId);
            
            $comment = Comment::create([
                'card_id' => $cardId,
                'user_id' => $request->user()->id,
                'comment_text' => $request->comment_text,
                'comment_type' => 'card'
            ]);
            
            $comment->load('user');
            
            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil ditambahkan',
                'data' => [
                    'id' => $comment->id,
                    'username' => $comment->user->username,
                    'comment_text' => $comment->comment_text,
                    'created_at' => $comment->created_at->toISOString(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add comment: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get Dashboard Stats (for all roles)
     * GET /api/dashboard
     */
    public function dashboard(Request $request)
    {
        try {
            $user = $request->user();
            $projects = $user->projects()->with(['boards.cards'])->get();
            
            $stats = [
                'total_projects' => $projects->count(),
                'total_tasks' => 0,
                'completed_tasks' => 0,
                'in_progress_tasks' => 0,
                'pending_reviews' => 0,
            ];
            
            foreach ($projects as $project) {
                $stats['total_tasks'] += $project->boards->sum(fn($b) => $b->cards->count());
                $stats['completed_tasks'] += $project->boards->sum(fn($b) => $b->cards->where('status', 'done')->count());
                $stats['in_progress_tasks'] += $project->boards->sum(fn($b) => $b->cards->where('status', 'in_progress')->count());
                $stats['pending_reviews'] += $project->boards->sum(fn($b) => $b->cards->where('status', 'review')->count());
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'username' => $user->username,
                        'full_name' => $user->full_name,
                        'role' => $user->role,
                    ],
                    'stats' => $stats,
                    'recent_projects' => $projects->take(5)->map(fn($p) => [
                        'id' => $p->id,
                        'project_name' => $p->project_name,
                    ]),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load dashboard: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Format time helper
     */
    private function formatTime($minutes)
    {
        if ($minutes == 0) return '00:00:00';
        
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        
        return sprintf('%02d:%02d:00', $hours, $mins);
    }
}
