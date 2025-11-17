<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    /**
     * Display the specified board (Kanban view)
     */
    /**
 * Show board with cards (Support multi-role: Admin, Team Lead, Developer)
 */
public function show(Project $project, Board $board)
{
    $user = Auth::user();
    
    // ============================================
    // AUTHORIZATION CHECK
    // ============================================
    
    // Check 1: Admin punya full access
    if ($user->role === 'admin') {
        // Admin bisa akses semua project
    } 
    // Check 2: Team Lead bisa akses project yang dia lead
    elseif ($user->role === 'teamlead') {
        $isMember = $project->members()
            ->where('user_id', $user->id)
            ->whereIn('role', ['admin', 'super_admin']) // Team lead biasanya role admin di project
            ->exists();
            
        if (!$isMember) {
            return redirect()->route('teamlead.dashboard')
                ->with('error', 'You do not have access to this project.');
        }
    }
    // Check 3: Developer hanya bisa akses project yang dia jadi member
    elseif ($user->role === 'developer') {
        $isMember = $project->members()
            ->where('user_id', $user->id)
            ->exists();
            
        if (!$isMember) {
            return redirect()->route('developer.dashboard')
                ->with('error', 'You are not a member of this project.');
        }
    }
    // Check 4: Designer
    elseif ($user->role === 'designer') {
        $isMember = $project->members()
            ->where('user_id', $user->id)
            ->exists();
            
        if (!$isMember) {
            return redirect()->route('designer.dashboard')
                ->with('error', 'You are not a member of this project.');
        }
    }
    
    // ============================================
    // LOAD DATA
    // ============================================
    
    // Load board with cards and related data
    $board->load([
        'cards' => function($query) {
            $query->orderBy('position');
        },
        'cards.assignments.user',  // Load assignments with user info
        'cards.comments.user',     // Load comments with user info
        'cards.subtasks',          // Load subtasks if exists
        'project'                  // Load project info
    ]);

    // Load project members
    $project->load(['members']);
    
    // Get user's assignments in this board (useful for all roles)
    $myAssignments = collect([]);
    if (in_array($user->role, ['developer', 'designer'])) {
        $cardIds = $board->cards->pluck('id');
        $myAssignments = \App\Models\CardAssignment::whereIn('card_id', $cardIds)
            ->where('user_id', $user->id)
            ->with('card')
            ->get();
    }
    
    // ============================================
    // ROLE-BASED VIEW ROUTING
    // ============================================
    
    // Tentukan view berdasarkan role
    switch ($user->role) {
        case 'admin':
            // Admin: Full access dengan edit/delete
            return view('admin.boards.show', compact('board', 'project'));
            
        case 'teamlead':
            // Team Lead: Monitoring & management view
            return view('teamlead.boards.show', compact('board', 'project'));
            
        case 'developer':
            // Developer: Read-only dengan fokus pada assigned tasks
            return view('developer.boards.show', compact('board', 'project', 'myAssignments'));
            
        case 'designer':
            // Designer: Similar to developer
            return view('designer.boards.show', compact('board', 'project', 'myAssignments'));
            
        default:
            abort(403, 'Unauthorized access.');
    }
}

    /**
     * Store a new board
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'board_name' => 'required|string|max:255',
        ]);

        $board = Board::create([
            'project_id' => $validated['project_id'],
            'board_name' => $validated['board_name'],
            'created_by' => auth()->id(),
        ]);


        return redirect()
            ->route('admin.projects.showproject', [$request->project_id])
            ->with('success', 'Board created successfully!');
            
        // return redirect()
        //     ->route('admin.boards.showproject', [$request->project_id, $board->id])
        //     ->with('success', 'Board created successfully!');
    }

    /**
     * Update board name
     */
    public function update(Request $request, Board $board)
    {
        $validated = $request->validate([
            'board_name' => 'required|string|max:255',
        ]);

        $board->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Board updated successfully'
        ]);
    }

    /**
     * Delete board
     */
    public function destroy(Board $board)
    {
        $projectId = $board->project_id;
        $board->delete();

        return redirect()
            ->route('admin.projects.show', $projectId)
            ->with('success', 'Board deleted successfully!');
    }
}
