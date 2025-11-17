<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Models\Card;
use App\Models\TimeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Show report selection page
     */
    public function index()
    {
        $user = Auth::user();

        $projects = Project::where('created_by', $user->id)
            ->orWhereHas('members', function($q) use ($user) {
                $q->where('project_members.user_id', $user->id)
                  ->whereIn('role', ['super_admin', 'admin']);
            })
            ->with(['boards.cards', 'members'])
            ->latest()
            ->get();

        return view('teamlead.reports.index', compact('projects'));
    }

    /**
     * Export project report to PDF
     */
    public function exportProjectReport(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $project = Project::with([
            'boards.cards.assignedMembers',
            'boards.cards.subtasks',
            'boards.cards.comments.user',
            'members',
            'creator'
        ])->findOrFail($validated['project_id']);

        // Check access
        $user = Auth::user();
        //if ($project->created_by !== $user->id && !$user->isAdmin($user->id)) {
        //    abort(403, 'Unauthorized access.');
        //}

        // Calculate statistics
        $stats = $this->calculateProjectStats($project, $validated);

        // Generate PDF
        $pdf = Pdf::loadView('teamlead.reports.project-pdf', [
            'project' => $project,
            'stats' => $stats,
            'startDate' => $validated['start_date'] ?? null,
            'endDate' => $validated['end_date'] ?? null,
            'generatedAt' => now(),
            'generatedBy' => Auth::user()
        ]);

        $filename = 'Project_Report_' . str_replace(' ', '_', $project->project_name) . '_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export team performance report to PDF
     */
    public function exportTeamReport(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $user = Auth::user();

        // Get projects
        $projectsQuery = Project::where('created_by', $user->id)
            ->orWhereHas('members', function($q) use ($user) {
                $q->where('project_members.user_id', $user->id)
                  ->whereIn('role', ['super_admin', 'admin']);
            });

        if (isset($validated['project_id'])) {
            $projectsQuery->where('id', $validated['project_id']);
        }

        $projects = $projectsQuery->with(['boards.cards', 'members'])->get();

        // Get team members with performance data
        $teamPerformance = $this->calculateTeamPerformance($projects, $validated);

        // Generate PDF
        $pdf = Pdf::loadView('teamlead.reports.team-pdf', [
            'projects' => $projects,
            'teamPerformance' => $teamPerformance,
            'startDate' => $validated['start_date'] ?? null,
            'endDate' => $validated['end_date'] ?? null,
            'generatedAt' => now(),
            'generatedBy' => Auth::user()
        ]);

        $filename = 'Team_Performance_Report_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export comprehensive report (All projects + Team)
     */
    public function exportComprehensiveReport(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $user = Auth::user();

        // Get all projects
        $projects = Project::where('created_by', $user->id)
            ->orWhereHas('members', function($q) use ($user) {
                $q->where('project_members.user_id', $user->id)
                  ->whereIn('role', ['super_admin', 'admin']);
            })
            ->with([
                'boards.cards.assignedMembers',
                'boards.cards.subtasks',
                'members',
                'creator'
            ])
            ->get();

        // Overall statistics
        $overallStats = [
            'total_projects' => $projects->count(),
            'total_tasks' => $projects->sum(fn($p) => $p->boards->sum(fn($b) => $b->cards->count())),
            'completed_tasks' => $projects->sum(fn($p) => $p->boards->sum(fn($b) => $b->cards->where('status', 'done')->count())),
            'in_progress_tasks' => $projects->sum(fn($p) => $p->boards->sum(fn($b) => $b->cards->where('status', 'in_progress')->count())),
            'total_team_members' => $projects->flatMap->members->unique('id')->count(),
        ];

        // Team performance
        $teamPerformance = $this->calculateTeamPerformance($projects, $validated);

        // Generate PDF
        $pdf = Pdf::loadView('teamlead.reports.comprehensive-pdf', [
            'projects' => $projects,
            'overallStats' => $overallStats,
            'teamPerformance' => $teamPerformance,
            'startDate' => $validated['start_date'] ?? null,
            'endDate' => $validated['end_date'] ?? null,
            'generatedAt' => now(),
            'generatedBy' => Auth::user()
        ])->setPaper('a4', 'portrait');

        $filename = 'Comprehensive_Report_' . now()->format('Y-m-d_His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Calculate project statistics
     */
    private function calculateProjectStats(Project $project, array $filters = [])
    {
        $cards = $project->boards->flatMap->cards;

        // Filter by date if provided
        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $cards = $cards->whereBetween('created_at', [
                Carbon::parse($filters['start_date']),
                Carbon::parse($filters['end_date'])
            ]);
        }

        $totalTasks = $cards->count();
        $completedTasks = $cards->where('status', 'done')->count();
        $inProgressTasks = $cards->where('status', 'in_progress')->count();
        $todoTasks = $cards->where('status', 'todo')->count();
        $reviewTasks = $cards->where('status', 'review')->count();

        $highPriorityTasks = $cards->where('priority', 'high')->count();
        $overdueTasks = $cards->filter(fn($c) => $c->is_overdue)->count();

        $totalEstimatedHours = $cards->sum('estimated_hours');
        $totalActualHours = $cards->sum('actual_hours');

        $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0;

        return [
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'in_progress_tasks' => $inProgressTasks,
            'todo_tasks' => $todoTasks,
            'review_tasks' => $reviewTasks,
            'high_priority_tasks' => $highPriorityTasks,
            'overdue_tasks' => $overdueTasks,
            'total_estimated_hours' => $totalEstimatedHours,
            'total_actual_hours' => $totalActualHours,
            'progress' => $progress,
            'on_time_percentage' => $totalTasks > 0 ? round((($totalTasks - $overdueTasks) / $totalTasks) * 100, 2) : 100,
        ];
    }

    /**
     * Calculate team performance
     */
    private function calculateTeamPerformance($projects, array $filters = [])
    {
        $projectIds = $projects->pluck('id');

        $teamMembers = User::whereHas('projects', function($query) use ($projectIds) {
                $query->whereIn('projects.id', $projectIds);
            })
            ->with(['cardAssignments', 'timeLogs'])
            ->get();

        return $teamMembers->map(function($member) use ($projectIds, $filters) {
            $assignments = $member->cardAssignments()
                ->whereHas('card.board', function($query) use ($projectIds) {
                    $query->whereIn('project_id', $projectIds);
                })
                ->get();

            $timeLogsQuery = $member->timeLogs()
                ->whereHas('card.board', function($query) use ($projectIds) {
                    $query->whereIn('project_id', $projectIds);
                });

            // Filter by date
            if (isset($filters['start_date']) && isset($filters['end_date'])) {
                $timeLogsQuery->whereBetween('start_time', [
                    Carbon::parse($filters['start_date']),
                    Carbon::parse($filters['end_date'])
                ]);
            }

            $timeLogs = $timeLogsQuery->get();
            $totalMinutes = $timeLogs->whereNotNull('duration_minutes')->sum('duration_minutes');

            $totalTasks = $assignments->count();
            $completedTasks = $assignments->where('assignment_status', 'completed')->count();
            $inProgressTasks = $assignments->where('assignment_status', 'in_progress')->count();

            return [
                'member' => $member,
                'total_tasks' => $totalTasks,
                'completed_tasks' => $completedTasks,
                'in_progress_tasks' => $inProgressTasks,
                'completion_rate' => $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0,
                'total_hours' => round($totalMinutes / 60, 2),
                'avg_hours_per_task' => $completedTasks > 0 ? round(($totalMinutes / 60) / $completedTasks, 2) : 0,
            ];
        })->sortByDesc('completed_tasks')->values();
    }
}
