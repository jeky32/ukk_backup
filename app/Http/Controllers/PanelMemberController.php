<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Project;
use App\Models\Comment;
use App\Models\TimeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PanelMemberController extends Controller
{
    public function index($projectId)
    {

        $project = Project::with(['boards.cards' => function($query) {
            $query->with(['assignments.user', 'subtasks', 'comments.user'])
                  ->orderBy('position');
        }])->findOrFail($projectId);

        // Ambil cards berdasarkan status
        $boards = [
            'todo' => [],
            'in_progress' => [],
            'review' => [],
            'done' => [],
            'blocker' => []

        ];

        foreach ($project->boards as $board) {
            foreach ($board->cards as $card) {
                // Cek apakah user adalah assignee dari card ini
                $isAssigned = $card->assignments->contains('user_id', Auth::id());

                if ($isAssigned) {
                    // Ambil active time log jika ada
                    $card->activeTimeLog = TimeLog::where('card_id', $card->id)
                        ->where('user_id', Auth::id())
                        ->whereNull('end_time')
                        ->first();

                    $boards[$card->status][] = $card;
                }
            }
        }

    $combinedBoards = array_merge(
    $boards['review'] ?? [],
    $boards['blocker'] ?? []
    );

        return view('panel.panelmember', compact('project', 'boards', 'combinedBoards'));
    }

    public function startWork($cardId)
    {
        try {
            $card = Card::findOrFail($cardId);

            // Cek apakah user adalah assignee
            $isAssigned = $card->assignments->contains('user_id', Auth::id());
            if (!$isAssigned) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak di-assign ke card ini'
                ]);
            }

            // Cek apakah sudah ada time log yang aktif
            $activeLog = TimeLog::where('user_id', Auth::id())
                ->whereNull('end_time')
                ->first();

            if ($activeLog) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda masih memiliki task yang sedang dikerjakan'
                ]);
            }

            DB::beginTransaction();

            // Ubah status card ke in_progress
            $card->update(['status' => 'in_progress']);

            // Buat time log baru
            TimeLog::create([
                'card_id' => $cardId,
                'user_id' => Auth::id(),
                'start_time' => now(),
            ]);

            // Update user status
            Auth::user()->update(['current_task_status' => 'working']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil memulai pekerjaan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulai pekerjaan: ' . $e->getMessage()
            ]);
        }
    }

    public function stopWork($cardId)
    {
        try {
            $card = Card::findOrFail($cardId);

            // Ambil active time log
            $activeLog = TimeLog::where('card_id', $cardId)
                ->where('user_id', Auth::id())
                ->whereNull('end_time')
                ->first();

            if (!$activeLog) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada time log aktif'
                ]);
            }

            DB::beginTransaction();

            // Update time log
            $startTime = \Carbon\Carbon::parse($activeLog->start_time);
            $endTime = now();
            $durationMinutes = $startTime->diffInMinutes($endTime);

            $activeLog->update([
                'end_time' => $endTime,
                'duration_minutes' => $durationMinutes
            ]);

            // Update actual hours di card
            $card->increment('actual_hours', $durationMinutes / 60);

			// Ubah status card ke todo
            $card->update(['status' => 'todo']);

            // Update user status
            Auth::user()->update(['current_task_status' => 'idle']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Time tracking dihentikan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghentikan time tracking: ' . $e->getMessage()
            ]);
        }
    }

    public function requestReview($cardId)
    {
        try {
            $card = Card::findOrFail($cardId);

            // Cek apakah user adalah assignee
            $isAssigned = $card->assignments->contains('user_id', Auth::id());
            if (!$isAssigned) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak di-assign ke card ini'
                ]);
            }

            DB::beginTransaction();

            // Stop time log jika ada yang aktif
            $activeLog = TimeLog::where('card_id', $cardId)
                ->where('user_id', Auth::id())
                ->whereNull('end_time')
                ->first();

            if ($activeLog) {
                $startTime = \Carbon\Carbon::parse($activeLog->start_time);
                $endTime = now();
                $durationMinutes = $startTime->diffInMinutes($endTime);

                $activeLog->update([
                    'end_time' => $endTime,
                    'duration_minutes' => $durationMinutes
                ]);

                $card->increment('actual_hours', $durationMinutes / 60);
            }

            // Ubah status card ke review
            $card->update(['status' => 'review']);

            // Update user status
            Auth::user()->update(['current_task_status' => 'idle']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Card berhasil dipindahkan ke Review'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal meminta review: ' . $e->getMessage()
            ]);
        }
    }

    public function requestBlocker($cardId)
    {
        try {
            $card = Card::findOrFail($cardId);

            // Cek apakah user adalah assignee
            $isAssigned = $card->assignments->contains('user_id', Auth::id());
            if (!$isAssigned) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak di-assign ke card ini'
                ]);
            }

            DB::beginTransaction();

            // Stop time log jika ada yang aktif
            $activeLog = TimeLog::where('card_id', $cardId)
                ->where('user_id', Auth::id())
                ->whereNull('end_time')
                ->first();

            if ($activeLog) {
                $startTime = \Carbon\Carbon::parse($activeLog->start_time);
                $endTime = now();
                $durationMinutes = $startTime->diffInMinutes($endTime);

                $activeLog->update([
                    'end_time' => $endTime,
                    'duration_minutes' => $durationMinutes
                ]);

                $card->increment('actual_hours', $durationMinutes / 60);
            }

            // Ubah status card ke blocker
            $card->update(['status' => 'blocker']);

            // Update user status
            Auth::user()->update(['current_task_status' => 'idle']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Card berhasil dipindahkan ke Review'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal meminta review: ' . $e->getMessage()
            ]);
        }
    }

    public function addComment(Request $request, $cardId)
    {
        $request->validate([
            'comment_text' => 'required|string|max:1000'
        ]);

        try {
            $card = Card::findOrFail($cardId);

            Comment::create([
                'card_id' => $cardId,
                'user_id' => Auth::id(),
                'comment_text' => $request->comment_text,
                'comment_type' => 'card'
            ]);

            return redirect()->back()->with('success', 'Komentar berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan komentar');
        }
    }
}
