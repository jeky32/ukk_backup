<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Board;
use App\Models\Subtask;
use App\Models\Comment;
use App\Models\TimeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CardController extends Controller
{
    /**
     * Add comment to card (untuk route cards.comment)
     */
    public function comment(Request $request, Card $card)
    {
        $validated = $request->validate([
            'comment_text' => 'required|string|max:1000',
        ]);

        try {
            // Create comment using model method or direct create
            if (method_exists($card, 'addComment')) {
                $comment = $card->addComment(Auth::id(), $validated['comment_text']);
            } else {
                $comment = Comment::create([
                    'card_id' => $card->card_id,
                    'user_id' => Auth::id(),
                    'comment_text' => $validated['comment_text'],
                ]);
            }

            $comment->load('user');

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Comment posted successfully',
                    'comment' => $comment
                ]);
            }

            return back()->with('success', 'Comment posted successfully!');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to post comment: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to post comment: ' . $e->getMessage());
        }
    }

    /**
     * Store a new card
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'board_id' => 'required|exists:boards,id',
            'card_title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,review,done',
            'priority' => 'nullable|in:low,medium,high',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|array',
            'assigned_to.*' => 'exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            // Get max position for this status
            $maxPosition = Card::where('board_id', $validated['board_id'])
                ->where('status', $validated['status'])
                ->max('position') ?? 0;

            // Create card
            $card = Card::create([
                'board_id' => $validated['board_id'],
                'card_title' => $validated['card_title'],
                'description' => $validated['description'],
                'status' => $validated['status'],
                'priority' => $validated['priority'] ?? 'medium',
                'due_date' => $validated['due_date'],
                'position' => $maxPosition + 1,
                'created_by' => Auth::id(),
            ]);

            // Assign members if provided
            if (!empty($validated['assigned_to'])) {
                foreach ($validated['assigned_to'] as $userId) {
                    $card->assignUser($userId);
                }
            }

            DB::commit();

            $board = Board::find($validated['board_id']);

            return redirect()
                ->route('admin.boards.show', [$board->project_id, $board->id])
                ->with('success', 'Card created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create card: ' . $e->getMessage());
        }
    }

    /**
     * Show card details
     */
    public function show(Card $card)
    {
        // Load all relationships
        $card->load([
            'board.project',
            'creator',
            'assignedMembers',
            'assignments.user',
            'comments.user',
            'subtasks.comments.user',
            'subtasks.timeLogs.user',
            'timeLogs.user'
        ]);

        return view('admin.cards.show', compact('card'));
    }

    /**
     * Update card
     */
    public function update(Request $request, Card $card)
    {
        $validated = $request->validate([
            'card_title' => 'sometimes|required|string|max:100',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|in:todo,in_progress,review,done',
            'priority' => 'nullable|in:low,medium,high',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'actual_hours' => 'nullable|numeric|min:0',
            'assigned_to' => 'nullable|array',
            'assigned_to.*' => 'exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            // Update card
            $card->update($validated);

            // Update assigned members if provided
            if (isset($validated['assigned_to'])) {
                // Remove old assignments
                $card->assignedMembers()->detach();

                // Add new assignments
                foreach ($validated['assigned_to'] as $userId) {
                    $card->assignUser($userId);
                }
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Card updated successfully',
                    'card' => $card->fresh()
                ]);
            }

            return back()->with('success', 'Card updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update card: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to update card: ' . $e->getMessage());
        }
    }

    /**
     * Delete card
     */
    public function destroy(Card $card)
    {
        $boardId = $card->board_id;
        $projectId = $card->board->project_id;

        try {
            $card->delete();

            return redirect()
                ->route('admin.boards.show', [$projectId, $boardId])
                ->with('success', 'Card deleted successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete card: ' . $e->getMessage());
        }
    }

    /**
     * Update card status (for drag & drop)
     */
    public function updateStatus(Request $request, Card $card)
    {
        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,review,done',
            'position' => 'required|integer|min:0',
        ]);

        try {
            // Update card status and position
            $card->moveToStatus($validated['status'], $validated['position']);

            return response()->json([
                'success' => true,
                'message' => 'Card status updated successfully',
                'card' => $card->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update card status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update card position within same status
     */
    public function updatePosition(Request $request, Card $card)
    {
        $validated = $request->validate([
            'position' => 'required|integer|min:0',
        ]);

        try {
            $card->updatePosition($validated['position']);

            return response()->json([
                'success' => true,
                'message' => 'Card position updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update position: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Move card (combined status and position update)
     */
    public function moveCard(Request $request, Card $card)
    {
        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,review,done',
            'position' => 'required|integer|min:0',
        ]);

        try {
            if ($card->status === $validated['status']) {
                // Same column, just update position
                $card->updatePosition($validated['position']);
            } else {
                // Different column, update status and position
                $card->moveToStatus($validated['status'], $validated['position']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Card moved successfully',
                'card' => $card->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to move card: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add comment to card (method lama, tetap dipertahankan)
     */
    public function addComment(Request $request, Card $card)
    {
        $validated = $request->validate([
            'comment_text' => 'required|string',
        ]);

        try {
            $comment = $card->addComment(Auth::id(), $validated['comment_text']);
            $comment->load('user');

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Comment added successfully',
                    'comment' => $comment
                ]);
            }

            return back()->with('success', 'Comment added successfully!');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to add comment: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to add comment: ' . $e->getMessage());
        }
    }

    /**
     * Add subtask to card (Task in checklist)
     */
    public function addTask(Request $request, Card $card)
    {
        $validated = $request->validate([
            'subtask_title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'estimated_hours' => 'nullable|numeric|min:0',
        ]);

        try {
            $subtask = $card->addSubtask(
                $validated['subtask_title'],
                $validated['description'] ?? null,
                $validated['estimated_hours'] ?? null
            );

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Subtask added successfully',
                    'subtask' => $subtask
                ]);
            }

            return back()->with('success', 'Subtask added successfully!');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to add subtask: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to add subtask: ' . $e->getMessage());
        }
    }

    /**
     * Update subtask
     */
    public function updateTask(Request $request, Card $card, Subtask $task)
    {
        $validated = $request->validate([
            'subtask_title' => 'sometimes|required|string|max:100',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|in:todo,in_progress,done',
            'estimated_hours' => 'nullable|numeric|min:0',
        ]);

        try {
            $task->update($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Subtask updated successfully',
                    'subtask' => $task->fresh()
                ]);
            }

            return back()->with('success', 'Subtask updated successfully!');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update subtask: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to update subtask: ' . $e->getMessage());
        }
    }

    /**
     * Delete subtask
     */
    public function deleteTask(Card $card, Subtask $task)
    {
        try {
            $task->delete();

            return response()->json([
                'success' => true,
                'message' => 'Subtask deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete subtask: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Start work on assignment
     */
    public function startWork(Request $request, Card $card)
    {
        try {
            $card->startWork(Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Work started'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to start work: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete work on assignment
     */
    public function completeWork(Request $request, Card $card)
    {
        try {
            $card->completeWork(Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Work completed'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete work: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Log time for card
     */
    public function logTime(Request $request, Card $card)
    {
        $validated = $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time',
            'description' => 'nullable|string',
            'subtask_id' => 'nullable|exists:subtasks,id',
        ]);

        try {
            if (isset($validated['subtask_id'])) {
                $subtask = Subtask::find($validated['subtask_id']);
                $timeLog = $subtask->logTime(
                    Auth::id(),
                    $validated['start_time'],
                    $validated['end_time'] ?? null,
                    $validated['description'] ?? null
                );
            } else {
                $timeLog = $card->logTime(
                    Auth::id(),
                    $validated['start_time'],
                    $validated['end_time'] ?? null,
                    $validated['description'] ?? null
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Time logged successfully',
                'time_log' => $timeLog
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to log time: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Duplicate card
     */
    public function duplicate(Card $card)
    {
        try {
            $newCard = $card->duplicate();

            return redirect()
                ->route('admin.cards.show', $newCard)
                ->with('success', 'Card duplicated successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to duplicate card: ' . $e->getMessage());
        }
    }
}
