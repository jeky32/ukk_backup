<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\Board;
use App\Models\Card;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Dashboard utama (redirect sesuai role user)
     */
    public function index()
    {
        $role = auth()->user()->role;

        switch ($role) {
            case 'admin':
                return $this->adminDashboard();
            case 'teamlead':
                return $this->teamLeadDashboard();
            case 'developer':
                return $this->developerDashboard();
            case 'designer':
                return $this->designerDashboard();
            default:
                abort(403, 'Role tidak dikenal');
        }
    }

    /**
     * ✅ HALAMAN ALL PROJECTS - My Projects + All Projects
     */
    public function manajemen_projects()
    {
        // ✅ FIXED: members.user → members
        $myProjects = Project::where('created_by', Auth::id())
            ->with(['boards.cards', 'members', 'creator'])
            ->latest()
            ->get();

        // ✅ FIXED: members.user → members
        $allProjects = Project::with(['boards.cards', 'members', 'creator'])
            ->latest()
            ->paginate(15);

        return view('admin.projects.index', compact('myProjects', 'allProjects'));
    }

    /**
     * ================= ADMIN DASHBOARD =================
     */
    public function adminDashboard()
    {
        $projects = Project::with(['boards.cards', 'members'])->get();

        $totalAllTasks = $projects->sum(function($project) {
            return $project->boards->flatMap->cards->count();
        });

        $completedAllTasks = $projects->sum(function($project) {
            return $project->boards->flatMap->cards->where('status', 'done')->count();
        });

        $rate = $totalAllTasks > 0 ? round(($completedAllTasks / $totalAllTasks) * 100) : 0;

        $activeTasks = $projects->sum(function($project) {
            return $project->boards->flatMap->cards->whereIn('status', ['todo', 'in_progress', 'review'])->count();
        });

        // ✅ FIXED: unique('user_id') → unique('id')
        $teamMembers = $projects->flatMap(function($project) {
            return $project->members;
        })->unique('id')->count();

        $projects->each(function ($project) {
            $totalCards = $project->boards->flatMap->cards->count();
            $doneCards = $project->boards->flatMap->cards->where('status', 'done')->count();

            $project->computed_data = [
                'progress' => $totalCards > 0 ? round(($doneCards / $totalCards) * 100) : 0,
                'status' => $this->determineProjectStatus($totalCards, $doneCards),
                'total_cards' => $totalCards,
                'done_cards' => $doneCards
            ];
        });

        $todayTasks = $this->getTodayTasks();
        $calendarDays = $this->getCalendarDays();
        $timelineData = $this->getTimelineData();
        
        // ✅ FIXED: select() dengan array
        $users = User::select(['id', 'username', 'full_name', 'email', 'role', 'avatar'])
            ->orderBy('full_name')
            ->get();

        return view('admin.dashboard', compact('projects', 'rate', 'activeTasks', 'teamMembers', 'todayTasks', 'calendarDays', 'timelineData', 'users'));
    }

    /**
     * API untuk mendapatkan time logs user per bulan
     */
    public function getTimeLogsCalendar(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));
        $userId = $request->input('user_id', Auth::id());

        $timeLogs = \App\Models\TimeLog::where('user_id', $userId)
            ->whereYear('start_time', $year)
            ->whereMonth('start_time', $month)
            ->selectRaw('DATE(start_time) as date, COUNT(*) as count, SUM(duration_minutes) as total_minutes')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $user = User::find($userId);

        $result = [];
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $hasWork = isset($timeLogs[$date]);

            $result[$date] = [
                'has_work' => $hasWork,
                'count' => $hasWork ? $timeLogs[$date]->count : 0,
                'total_minutes' => $hasWork ? $timeLogs[$date]->total_minutes : 0,
                'total_hours' => $hasWork ? round($timeLogs[$date]->total_minutes / 60, 1) : 0
            ];
        }

        return response()->json([
            'success' => true,
            'year' => $year,
            'month' => $month,
            'user' => [
                'id' => $user->id,
                'name' => $user->full_name ?? $user->username,
                'role' => $user->role
            ],
            'data' => $result
        ]);
    }

    /**
     * ✅ FIXED: All projects page
     */
    public function allprojects()
    {
        // ✅ FIXED: members.user → members
        $projects = Project::with(['boards.cards', 'members'])->get();

        $totalAllTasks = $projects->sum(function($project) {
            return $project->boards->flatMap->cards->count();
        });

        $completedAllTasks = $projects->sum(function($project) {
            return $project->boards->flatMap->cards->where('status', 'done')->count();
        });

        $rate = $totalAllTasks > 0 ? round(($completedAllTasks / $totalAllTasks) * 100) : 0;

        $activeTasks = $projects->sum(function($project) {
            return $project->boards->flatMap->cards->whereIn('status', ['todo', 'in_progress', 'review'])->count();
        });

        // ✅ FIXED: select() dengan array
        $allUsers = User::select(['id', 'username', 'full_name', 'email', 'role', 'phone', 'avatar'])
            ->orderBy('full_name')
            ->get();

        $teamMembers = $allUsers->count();

        $projects->each(function ($project) {
            $totalCards = $project->boards->flatMap->cards->count();
            $doneCards = $project->boards->flatMap->cards->where('status', 'done')->count();

            $project->computed_data = [
                'progress' => $totalCards > 0 ? round(($doneCards / $totalCards) * 100) : 0,
                'status' => $this->determineProjectStatus($totalCards, $doneCards),
                'total_cards' => $totalCards,
                'done_cards' => $doneCards
            ];
        });

        $todayTasks = $this->getTodayTasks();
        $calendarDays = $this->getCalendarDays();
        $timelineData = $this->getTimelineData();

        return view('admin.allprojects', compact('projects', 'rate', 'activeTasks', 'teamMembers', 'todayTasks', 'calendarDays', 'timelineData', 'allUsers'));
    }

    public function app()
    {
        $todayTasks = $this->getTodayTasks();
        $calendarDays = $this->getCalendarDays();
        $timelineData = $this->getTimelineData();

        return view('admin.content', [
            'todayTasks' => $todayTasks,
            'calendarDays' => $calendarDays,
            'timelineData' => $timelineData,
        ]);
    }

    /**
     * ✅ FIXED: Get today tasks
     */
    private function getTodayTasks()
    {
        $projects = Project::with([
            'boards.cards.assignments.user', // ✅ FIXED
            'members'
        ])
        ->where('created_by', Auth::id())
        ->orWhereHas('members', function($q) {
            $q->where('user_id', Auth::id());
        })
        ->get();

        $tasks = [];

        foreach ($projects as $project) {
            foreach ($project->boards as $board) {
                foreach ($board->cards->take(2) as $card) {
                    // ✅ FIXED: assignments instead of assignedUsers
                    if ($card->relationLoaded('assignments') && $card->assignments->count() > 0) {
                        $members = $card->assignments->map(function($assignment) {
                            return $assignment->user->full_name ?? $assignment->user->username;
                        })->toArray();
                    } else {
                        $members = $project->members->map(function($member) {
                            return $member->full_name ?? $member->username;
                        })->toArray();
                    }

                    $tasks[] = [
                        'id' => $card->id,
                        'title' => $card->card_title ?? 'Untitled Task',
                        'description' => $card->description ?? 'No description',
                        'progress' => rand(30, 100),
                        'members' => $members,
                        'status' => $card->status ?? 'todo'
                    ];
                }
            }
        }

        return array_slice($tasks, 0, 2);
    }

    private function getCalendarDays()
    {
        $now = now();
        $firstDay = $now->copy()->startOfMonth();
        $lastDay = $now->copy()->endOfMonth();

        $days = [];

        $startingDayOfWeek = $firstDay->dayOfWeek;
        if ($startingDayOfWeek > 0) {
            $prevMonth = $firstDay->copy()->subDay();
            for ($i = $startingDayOfWeek - 1; $i >= 0; $i--) {
                $days[] = [
                    'day' => $prevMonth->copy()->subDays($i)->day,
                    'date' => $prevMonth->copy()->subDays($i)->toDateString(),
                    'isOtherMonth' => true,
                    'isToday' => false,
                    'isSelected' => false
                ];
            }
        }

        for ($day = 1; $day <= $lastDay->day; $day++) {
            $date = $now->copy()->setDay($day);
            $isToday = $date->isToday();

            $days[] = [
                'day' => $day,
                'date' => $date->toDateString(),
                'isOtherMonth' => false,
                'isToday' => $isToday,
                'isSelected' => $isToday
            ];
        }

        $remainingDays = 42 - count($days);
        for ($i = 1; $i <= $remainingDays; $i++) {
            $days[] = [
                'day' => $i,
                'date' => $lastDay->copy()->addDays($i)->toDateString(),
                'isOtherMonth' => true,
                'isToday' => false,
                'isSelected' => false
            ];
        }

        return $days;
    }

    private function getTimelineData()
    {
        return [
            ['id' => 'interview', 'title' => 'Interview', 'date' => '12', 'height' => 80, 'color' => '#FF7F50', 'color-class' => 'orange-500'],
            ['id' => 'ideate', 'title' => 'Ideate', 'date' => '13', 'height' => 65, 'color' => '#10B981', 'color-class' => 'teal-500'],
            ['id' => 'wireframe', 'title' => 'Wireframe', 'date' => '14', 'height' => 55, 'color' => '#7C3AED', 'color-class' => 'purple-500'],
            ['id' => 'design', 'title' => 'Design', 'date' => '15', 'height' => 75, 'color' => '#3B82F6', 'color-class' => 'blue-500'],
            ['id' => 'develop', 'title' => 'Develop', 'date' => '16', 'height' => 90, 'color' => '#1E293B', 'color-class' => 'gray-900'],
            ['id' => 'test', 'title' => 'Test', 'date' => '17', 'height' => 70, 'color' => '#10B981', 'color-class' => 'teal-500'],
            ['id' => 'deploy', 'title' => 'Deploy', 'date' => '18', 'height' => 85, 'color' => '#FF7F50', 'color-class' => 'orange-500'],
        ];
    }

    public function dash()
    {
        $user = Auth::user();

        $projects = Project::with(['boards.cards', 'members', 'creator'])
            ->where('created_by', $user->id)
            ->orWhereHas('members', function($query) use ($user) {
                $query->where('project_members.user_id', $user->id);
            })
            ->latest()
            ->get();

        return view('admin.dashboard', compact('projects'));
    }

    public function showproject(Project $project)
    {
        $project->load([
            'boards.cards',
            'members',
            'creator'
        ]);

        return view('admin.projects.showproject', compact('project'));
    }

    /**
     * ================= ADMIN CRUD PROJECT =================
     */
    
    public function create()
    {
        $this->authorizeRole('admin');
        
        // ✅ FIXED: select() dengan array
        $teamLeads = User::where('role', 'teamlead')
            ->select(['id', 'username', 'full_name', 'email'])
            ->orderBy('full_name')
            ->get();
        
        return view('admin.projects.create', compact('teamLeads'));
    }

    public function store(Request $request)
    {
        $this->authorizeRole('admin');

        $validated = $request->validate([
            'project_name' => 'required|string|max:255|unique:projects,project_name',
            'description'  => 'nullable|string|max:1000',
            'thumbnail'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'deadline'     => 'nullable|date|after_or_equal:today',
            'team_lead_id' => 'required|exists:users,id',
        ], [
            'project_name.required' => 'Nama project harus diisi',
            'project_name.unique'   => 'Nama project sudah digunakan',
            'team_lead_id.required' => 'Team Lead harus dipilih',
            'team_lead_id.exists'   => 'Team Lead tidak ditemukan',
        ]);

        DB::beginTransaction();

        try {
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $filename = time() . '_' . Str::slug($validated['project_name']) . '.' . $file->getClientOriginalExtension();
                $thumbnailPath = $file->storeAs('project-thumbnails', $filename, 'public');
            }

            $project = Project::create([
                'project_name' => $validated['project_name'],
                'description'  => $validated['description'],
                'thumbnail'    => $thumbnailPath,
                'created_by'   => auth()->id(),
                'deadline'     => $validated['deadline'],
                'status'       => 'pending',
            ]);

            ProjectMember::create([
                'project_id' => $project->id,
                'user_id'    => auth()->id(),
                'role'       => 'super_admin',
                'joined_at'  => now(),
            ]);

            ProjectMember::create([
                'project_id' => $project->id,
                'user_id'    => $validated['team_lead_id'],
                'role'       => 'admin',
                'joined_at'  => now(),
            ]);

            Board::create([
                'project_id' => $project->id,
                'board_name' => 'To Do',
                'description' => 'Default board',
                'position' => 1,
            ]);

            DB::commit();

            $teamLead = User::find($validated['team_lead_id']);

            return redirect()
                ->route('admin.allprojects')
                ->with('success', '🎉 Project "' . $project->project_name . '" berhasil dibuat dan diserahkan ke ' . $teamLead->full_name);

        } catch (\Exception $e) {
            DB::rollBack();
            
            if (isset($thumbnailPath) && Storage::disk('public')->exists($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }

            \Log::error('Failed to create project', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'data' => $request->all()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Gagal membuat project: ' . $e->getMessage());
        }
    }

    public function show($project_id)
    {
        $this->authorizeRole('admin');

        // ✅ FIXED: members.user → members
        $project = Project::with(['boards.cards', 'members'])->findOrFail($project_id);
        $project->load(['creator', 'members']);

        // ✅ FIXED: pluck('user_id') → pluck('id')
        $existingMemberIds = $project->members->pluck('id')->toArray();
        $availableUsers = User::whereNotIn('id', $existingMemberIds)->get();

        return view('admin.projects.show', compact('project', 'availableUsers'));
    }

    public function edit($project_id)
    {
        $this->authorizeRole('admin');
        $project = Project::findOrFail($project_id);
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, $project_id)
    {
        $this->authorizeRole('admin');

        $request->validate([
            'project_name' => 'required|string|max:255',
            'description'  => 'nullable|string',
            'thumbnail'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deadline'     => 'nullable|date|after_or_equal:today',
        ]);

        try {
            $project = Project::findOrFail($project_id);

            if ($request->hasFile('thumbnail')) {
                if ($project->thumbnail) {
                    Storage::disk('public')->delete($project->thumbnail);
                }

                $thumbnailPath = $request->file('thumbnail')->store('project-thumbnails', 'public');

                $project->update([
                    'project_name' => $request->project_name,
                    'description'  => $request->description,
                    'thumbnail'    => $thumbnailPath,
                    'deadline'     => $request->deadline,
                ]);
            } else {
                $project->update([
                    'project_name' => $request->project_name,
                    'description'  => $request->description,
                    'deadline'     => $request->deadline,
                ]);
            }

            return redirect()->route('admin.projects.showproject', [$project_id])->with('success', 'Project berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Gagal memperbarui project: ' . $e->getMessage());
        }
    }

    public function destroy($project_id)
    {
        $this->authorizeRole('admin');

        try {
            $project = Project::findOrFail($project_id);

            if ($project->thumbnail) {
                Storage::disk('public')->delete($project->thumbnail);
            }

            $project->delete();

            return redirect()->route('admin.projects.index')->with('success', 'Project berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Gagal menghapus project: ' . $e->getMessage());
        }
    }

    public function deleteThumbnail($project_id)
    {
        $this->authorizeRole('admin');

        try {
            $project = Project::findOrFail($project_id);

            if ($project->thumbnail) {
                Storage::disk('public')->delete($project->thumbnail);
                $project->update(['thumbnail' => null]);

                return redirect()->back()->with('success', 'Thumbnail berhasil dihapus!');
            }

            return redirect()->back()->with('info', 'Project tidak memiliki thumbnail.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus thumbnail: ' . $e->getMessage());
        }
    }

    /**
     * ================= TEAM LEAD DASHBOARD =================
     */
    public function teamLeadDashboard()
    {
        $userId = auth()->id();

        // ✅ FIXED: members.user → members
        $projects = Project::whereHas('members', function ($q) use ($userId) {
            $q->where('user_id', $userId)
              ->whereIn('role', ['admin', 'super_admin']);
        })->with(['boards.cards', 'members'])->get();

        // ✅ FIXED: select() dengan array
        $users = User::select(['id', 'username', 'full_name', 'email', 'role', 'avatar'])
            ->orderBy('full_name')
            ->get();

        return view('teamlead.dashboard', compact('projects', 'users'));
    }

    public function teamLeadShow($project_id)
    {
        $userId = auth()->id();

        // ✅ FIXED: members.user → members
        $project = Project::whereHas('members', function ($q) use ($userId) {
            $q->where('user_id', $userId)
              ->whereIn('role', ['admin', 'super_admin']);
        })->with(['boards.cards', 'members'])->findOrFail($project_id);

        return view('teamlead.projects.show', compact('project'));
    }

    /**
     * ================= DEVELOPER DASHBOARD =================
     */
    public function developerDashboard()
    {
        $userId = auth()->id();

        $cards = Card::whereHas('assignments', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with(['board.project'])->get();

        return view('developer.dashboard', compact('cards'));
    }

    /**
     * ================= DESIGNER DASHBOARD =================
     */
    public function designerDashboard()
    {
        $userId = auth()->id();

        $cards = Card::whereHas('assignments', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with(['board.project'])->get();

        return view('developer.dashboard', compact('cards'));
    }

    /**
     * ================= HELPER FUNCTION =================
     */
    private function authorizeRole($role)
    {
        if (auth()->user()->role !== $role) {
            abort(403, 'Akses ditolak untuk role ini');
        }
    }

    private function determineProjectStatus($totalCards, $doneCards)
    {
        if ($totalCards === 0) {
            return 'Not Started';
        }

        $progress = $totalCards > 0 ? round(($doneCards / $totalCards) * 100) : 0;

        if ($progress == 100) {
            return 'Completed';
        } elseif ($progress > 0) {
            return 'In Progress';
        } else {
            return 'Low Progress';
        }
    }
}
