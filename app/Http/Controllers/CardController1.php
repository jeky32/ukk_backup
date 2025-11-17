<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Board;
use App\Models\CardAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
class CardController extends Controller
{
    // ===================================================
    // 👤 TEAM LEAD ROUTES
    // ===================================================

    public function index(Board $board)
    {
        if (Auth::user()->role !== 'team_lead') {
            abort(403, 'Hanya Team Lead yang bisa melihat daftar card');
        }

        $cards = Card::with(['assignments.user', 'subtasks'])
            ->where('board_id', $board->board_id)
            ->get();

        return view('teamlead.cards.index', compact('cards', 'board'));
    }

    public function create(Board $board)
    {
        if (Auth::user()->role !== 'team_lead') abort(403);

        return view('teamlead.cards.create', compact('board'));
    }

    // public function store(Request $request, Board $board)
    // {
    //     if (Auth::user()->role !== 'team_lead') abort(403);

    //     $request->validate([
    //         'card_title'      => 'required|string|max:255',
    //         'description'     => 'nullable|string',
    //         'priority'        => 'required|in:low,medium,high',
    //         'estimated_hours' => 'nullable|numeric|min:0',
    //         'due_date'        => 'nullable|date|after_or_equal:today',
    //         'username'        => 'required|string'
    //     ]);

    //     $user = User::where('username', $request->username)
    //         ->whereIn('role', ['developer', 'designer'])
    //         ->first();

    //     if (!$user) {
    //         throw ValidationException::withMessages([
    //             'username' => "❌ Username {$request->username} tidak valid atau bukan developer/designer.",
    //         ]);
    //     }

    //     $hasActiveCard = CardAssignment::where('user_id', $user->user_id)
    //         ->whereHas('card', function ($q) {
    //             $q->whereIn('status', ['todo', 'in_progress', 'review']);
    //         })
    //         ->exists();

    //     if ($hasActiveCard) {
    //         throw ValidationException::withMessages([
    //             'username' => "❌ User {$user->username} sedang ada tugas lain. Selesaikan dulu sebelum ambil card baru.",
    //         ]);
    //     }

    //     $lastPosition = Card::where('board_id', $board->board_id)->max('position');
    //     $position = $lastPosition ? $lastPosition + 1 : 1;

    //     $card = Card::create([
    //         'board_id'        => $board->board_id,
    //         'card_title'      => $request->card_title,
    //         'description'     => $request->description,
    //         'priority'        => $request->priority,
    //         'estimated_hours' => $request->estimated_hours,
    //         'due_date'        => $request->due_date,
    //         'status'          => 'todo',
    //         'position'        => $position,
    //         'created_by'      => Auth::id(),
    //     ]);

    //     CardAssignment::create([
    //         'card_id'          => $card->card_id,
    //         'user_id'          => $user->user_id,
    //         'assignment_status'=> 'assigned',
    //         'assigned_at'      => now()
    //     ]);

    //     return redirect()->route('teamlead.cards.index', $board)
    //         ->with('success', '✅ Card berhasil dibuat & ditugaskan!');
    // }
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
//            'estimated_hours' => 'nullable|numeric|min:0',
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
//                'estimated_hours' => $validated['estimated_hours'],
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
    public function edit(Board $board, Card $card)
    {
        if (Auth::user()->role !== 'team_lead') abort(403);

        $card->load('assignments.user');
        return view('teamlead.cards.edit', compact('board', 'card'));
    }

    public function update(Request $request, Board $board, Card $card)
    {
        if (Auth::user()->role !== 'team_lead') abort(403);

        $request->validate([
            'card_title'      => 'required|string|max:255',
            'description'     => 'nullable|string',
            'priority'        => 'required|in:low,medium,high',
            'estimated_hours' => 'nullable|numeric|min:0',
            'due_date'        => 'nullable|date|after_or_equal:today',
            'username'        => 'required|string'
        ]);

        $card->update([
            'card_title'      => $request->card_title,
            'description'     => $request->description,
            'priority'        => $request->priority,
            'estimated_hours' => $request->estimated_hours,
            'due_date'        => $request->due_date,
        ]);

        // Hapus assignment lama
        CardAssignment::where('card_id', $card->card_id)->delete();

        $user = User::where('username', $request->username)
            ->whereIn('role', ['developer', 'designer'])
            ->first();

        if ($user) {
            $hasActiveCard = CardAssignment::where('user_id', $user->user_id)
                ->where('card_id', '!=', $card->card_id)
                ->whereHas('card', function ($q) {
                    $q->whereIn('status', ['todo', 'in_progress', 'review']);
                })
                ->exists();

            if ($hasActiveCard) {
                throw ValidationException::withMessages([
                    'username' => "❌ User {$user->username} sedang ada tugas lain. Selesaikan dulu sebelum ambil card baru.",
                ]);
            }

            CardAssignment::create([
                'card_id'          => $card->card_id,
                'user_id'          => $user->user_id,
                'assignment_status'=> 'assigned',
                'assigned_at'      => now()
            ]);
        }

        return redirect()->route('teamlead.cards.index', $board)
            ->with('success', '✅ Card berhasil diperbarui!');
    }

    public function destroy(Board $board, Card $card)
    {
        if (Auth::user()->role !== 'team_lead') abort(403);

        $card->delete();
        return redirect()->route('teamlead.cards.index', $board)
            ->with('success', '🗑️ Card berhasil dihapus!');
    }

    // ===================================================
    // 👨‍💻 DEVELOPER ROUTES
    // ===================================================

    public function developerDashboard()
    {
        $user = auth()->user();

        // Ambil semua card yang ditugaskan ke developer ini
        $cards = Card::whereHas('assignments', function ($q) use ($user) {
            $q->where('user_id', $user->user_id);
        })
        ->with('board.project')
        ->get();

        return view('developer.dashboard', compact('cards'));
    }

    public function start($card_id)
    {
        $card = Card::findOrFail($card_id);

        // Pastikan yang mengakses adalah developer yang ditugaskan
        $assignment = $card->assignments()->where('user_id', auth()->id())->firstOrFail();

        if ($card->status !== 'todo') {
            return back()->with('error', 'Card sudah dimulai atau selesai.');
        }

        $card->update(['status' => 'in_progress']);

        return back()->with('success', '🚀 Card dimulai!');
    }

    public function complete($card_id)
    {
        $card = Card::findOrFail($card_id);

        $assignment = $card->assignments()->where('user_id', auth()->id())->firstOrFail();

        if ($card->status !== 'in_progress') {
            return back()->with('error', 'Card belum dimulai atau sudah selesai.');
        }

        $card->update(['status' => 'done']);

        return back()->with('success', '✅ Card selesai!');
    }

    // ===================================================
    // 🎨 DESIGNER ROUTES
    // ===================================================
    public function designerDashboard()
    {
        $user = auth()->user();

        $cards = Card::whereHas('assignments', function ($q) use ($user) {
            $q->where('user_id', $user->user_id);
        })
        ->with('board.project')
        ->get();

        return view('designer.dashboard', compact('cards'));
    }
}
