<?php
namespace App\Http\Controllers;
use App\Models\Project;
use App\Models\User;
use App\Models\Comment;
use App\Models\Card;
use App\Models\CardAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $projects = $user->projects()->with(['boards.cards', 'members'])->get();

        // Hitung statistik
        $totalTasks = $projects->sum(function($project) {
            return $project->boards->sum(fn($board) => $board->cards->count());
        });

        $completedTasks = $projects->sum(function($project) {
            return $project->boards->sum(fn($board) => $board->cards->where('status', 'done')->count());
        });

        $rate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        $teamMembers = $projects->pluck('members')->flatten()->unique('id')->count();

        return view('admin.dashboard', compact('projects', 'rate', 'teamMembers'));
    }

    /**
     * Show team performance report
     */
    public function teamReport() 
    {
        $user = Auth::user();

        // Get projects
        $projects = Project::where('created_by', $user->id)
            ->orWhereHas('members', function($q) use ($user) {
                $q->where('users.id', $user->id)
                  ->whereIn('role', ['super_admin', 'admin']);
            })
            ->get();

        // Get all team members
        $teamMembers = User::whereHas('projects', function($query) use ($projects) {
                $query->whereIn('projects.id', $projects->pluck('id'));
            })
            ->where('id', '!=', $user->id)
            ->get()
            ->map(function($member) {
                return [
                    'member' => $member,
                    'total_tasks' => $member->cardAssignments()->count(),
                    'completed_tasks' => $member->cardAssignments()
                        ->where('assignment_status', 'completed')
                        ->count(),
                    'total_hours' => $member->timeLogs()
                        ->whereNotNull('duration_minutes')
                        ->sum('duration_minutes') / 60,
                    'this_week_hours' => $member->timeLogs()
                        ->whereBetween('start_time', [now()->startOfWeek(), now()->endOfWeek()])
                        ->sum('duration_minutes') / 60,
                ];
            });

        return view('teamlead.team-report', compact('teamMembers'));
    }

}
