<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Project;
use App\Models\Comment;
use App\Models\TimeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PanelTeamLeadController extends Controller
{
    public function index($projectId)
    {
        $project = Project::with(['boards.cards' => function($query) {
            $query->with(['assignments.user', 'subtasks', 'comments.user'])
                  ->orderBy('position');
        }])->findOrFail($projectId);

        // Ambil cards berdasarkan status dan hitung total waktu
        $boards = [
            'todo' => [],
            'in_progress' => [],
            'review' => [],
            'done' => [],
            'blocker' => []
        ];

        foreach ($project->boards as $board) {
            foreach ($board->cards as $card) {
                // Hitung total time untuk setiap role
                $timeLogs = TimeLog::where('card_id', $card->id)
                    ->whereNotNull('end_time')
                    ->with('user')
                    ->get();

                $totalMinutes = $timeLogs->sum('duration_minutes');
                $devMinutes = $timeLogs->where('user.role', 'developer')->sum('duration_minutes');
                $designerMinutes = $timeLogs->where('user.role', 'designer')->sum('duration_minutes');

                // Format waktu
                $card->total_time = $this->formatTime($totalMinutes);
                $card->dev_time = $this->formatTime($devMinutes);
                $card->designer_time = $this->formatTime($designerMinutes);

                $boards[$card->status][] = $card;
            }
        }

    $combinedBoards = array_merge(
    $boards['review'] ?? [],
    $boards['blocker'] ?? []
    );
        return view('panel.panelteamlead', compact('project', 'boards', 'combinedBoards'));
    }

    private function formatTime($minutes)
    {
        if ($minutes == 0) {
            return '00:00:00';
        }

        $hours = floor($minutes / 60);
        $mins = $minutes % 60;

        return sprintf('%02d:%02d:%02d', $hours, $mins, 0);
    }

	public function changeStatus(Request $request, $cardId)
    {
        $request->validate([
            'status' => 'required|in:todo,in_progress,review,done'
        ]);

        try {
            $card = Card::findOrFail($cardId);

            DB::beginTransaction();

            // Jika status berubah dari review ke todo, stop semua active time logs
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

                    $card->increment('actual_hours', $durationMinutes / 60);
                }
            }

            // Mapping status ke board name
            $statusToBoardName = [
                'todo' => 'To Do',
                'in_progress' => 'In Progress',
                'review' => 'Review',
                'done' => 'Done'
            ];

            // Cari board berdasarkan board_name sesuai status
            $boardName = $statusToBoardName[$request->status];
            $board = \App\Models\Board::where('project_id', $card->board->project_id)
                ->where('board_name', $boardName)
                ->first();

            if (!$board) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "Board '{$boardName}' tidak ditemukan"
                ]);
            }

            // Update status card dan board_id
            $card->update([
                'status' => $request->status,
                'board_id' => $board->id
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Status berhasil diubah');
            // return response()->json([
                // 'success' => true,
                // 'message' => 'Status berhasil diubah'
            // ]);
        } catch (\Exception $e) {
            DB::rollBack();
            // return response()->json([
                // 'success' => false,
                // 'message' => 'Gagal mengubah status: ' . $e->getMessage()
            // ]);
			return redirect()->back()->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }


    // public function changeStatus(Request $request, $cardId)
    // {
		// exit();
        // $request->validate([
            // 'status' => 'required|in:todo,in_progress,review,done'
        // ]);

        // try {
            // $card = Card::findOrFail($cardId);

            // DB::beginTransaction();

            // // Jika status berubah dari review ke todo, stop semua active time logs
            // if ($card->status == 'review' && $request->status == 'todo') {
                // $activeLogs = TimeLog::where('card_id', $cardId)
                    // ->whereNull('end_time')
                    // ->get();

                // foreach ($activeLogs as $log) {
                    // $startTime = \Carbon\Carbon::parse($log->start_time);
                    // $endTime = now();
                    // $durationMinutes = $startTime->diffInMinutes($endTime);

                    // $log->update([
                        // 'end_time' => $endTime,
                        // 'duration_minutes' => $durationMinutes
                    // ]);

                    // $card->increment('actual_hours', $durationMinutes / 60);
                // }
            // }

            // // Update status card
            // $card->update(['status' => $request->status]);

            // DB::commit();

            // return response()->json([
                // 'success' => true,
                // 'message' => 'Status berhasil diubah'
            // ]);
        // } catch (\Exception $e) {
            // DB::rollBack();
            // return response()->json([
                // 'success' => false,
                // 'message' => 'Gagal mengubah status: ' . $e->getMessage()
            // ]);
        // }
    // }

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
