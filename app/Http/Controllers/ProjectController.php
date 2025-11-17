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
        // My Projects (created by current user)
        $myProjects = Project::where('created_by', Auth::id())
            ->with(['boards.cards', 'members.user', 'creator'])
            ->latest()
            ->get();

        // All Projects (paginated)
        $allProjects = Project::with(['boards.cards', 'members.user', 'creator'])
            ->latest()
            ->paginate(15);

        return view('admin.projects.index', compact('myProjects', 'allProjects'));
    }

    /**
     * ================= ADMIN DASHBOARD =================
     */
    public function adminDashboard()
    {
        // Ambil semua proyek beserta board dan member-nya
        $projects = Project::with(['boards.cards', 'members.user'])->get();

        // ✅ HITUNG STATISTIK DASHBOARD
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

        $teamMembers = $projects->flatMap(function($project) {
            return $project->members;
        })->unique('user_id')->count();

        // ✅ PERBAIKI: Hitung progress dengan approach yang lebih aman
        $projects->each(function ($project) {
            $totalCards = $project->boards->flatMap->cards->count();
            $doneCards = $project->boards->flatMap->cards->where('status', 'done')->count();

            // ✅ GUNAKAN computed_data array untuk hindari undefined property warning
            $project->computed_data = [
                'progress' => $totalCards > 0 ? round(($doneCards / $totalCards) * 100) : 0,
                'status' => $this->determineProjectStatus($totalCards, $doneCards),
                'total_cards' => $totalCards,
                'done_cards' => $doneCards
            ];
        });

         // Get today's tasks
        $todayTasks = $this->getTodayTasks();

        // Get calendar days
        $calendarDays = $this->getCalendarDays();

        // Get timeline data
        $timelineData = $this->getTimelineData();

        // return view('admin.content', [
        //     'todayTasks' => $todayTasks,
        //     'calendarDays' => $calendarDays,
        //     'timelineData' => $timelineData,
        // ]);

        // ✅ TAMBAHAN BARU: Ambil semua users dari database
        $users = User::select('id', 'username', 'full_name', 'email', 'role', 'avatar')
            ->orderBy('full_name')
            ->get();

        // ✅ PERUBAHAN: Tambahkan 'users' ke compact
        return view('admin.dashboard', compact('projects', 'rate', 'activeTasks', 'teamMembers', 'todayTasks', 'calendarDays', 'timelineData', 'users'));
    }

    /**
	 * API untuk mendapatkan time logs user per bulan
	 */
	public function getTimeLogsCalendar(Request $request)
	{
		$year = $request->input('year', date('Y'));
		$month = $request->input('month', date('m'));
		$userId = $request->input('user_id', Auth::id()); // Ambil dari request atau current user

		// Ambil semua time_logs user di bulan tersebut
		$timeLogs = \App\Models\TimeLog::where('user_id', $userId)
			->whereYear('start_time', $year)
			->whereMonth('start_time', $month)
			->selectRaw('DATE(start_time) as date, COUNT(*) as count, SUM(duration_minutes) as total_minutes')
			->groupBy('date')
			->get()
			->keyBy('date');

		// Ambil info user
		$user = User::find($userId); // ✅ PERBAIKAN: Gunakan User tanpa backslash

		// Format response
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

    public function allprojects()
{
    // Ambil semua proyek beserta board dan member-nya
    $projects = Project::with(['boards.cards', 'members.user'])->get();

    // ✅ HITUNG STATISTIK DASHBOARD
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

    // ✅ PERBAIKAN: Ambil semua users dari database (BUKAN dari project members)
    $allUsers = User::select('id', 'username', 'full_name', 'email', 'role', 'phone', 'avatar')
        ->orderBy('full_name')
        ->get();

    $teamMembers = $allUsers->count();

    // ✅ PERBAIKI: Hitung progress dengan approach yang lebih aman
    $projects->each(function ($project) {
        $totalCards = $project->boards->flatMap->cards->count();
        $doneCards = $project->boards->flatMap->cards->where('status', 'done')->count();

        // ✅ GUNAKAN computed_data array untuk hindari undefined property warning
        $project->computed_data = [
            'progress' => $totalCards > 0 ? round(($doneCards / $totalCards) * 100) : 0,
            'status' => $this->determineProjectStatus($totalCards, $doneCards),
            'total_cards' => $totalCards,
            'done_cards' => $doneCards
        ];
    });

    // Get today's tasks
    $todayTasks = $this->getTodayTasks();

    // Get calendar days
    $calendarDays = $this->getCalendarDays();

    // Get timeline data
    $timelineData = $this->getTimelineData();

    // ✅ RETURN dengan allUsers
    return view('admin.allprojects', compact('projects', 'rate', 'activeTasks', 'teamMembers', 'todayTasks', 'calendarDays', 'timelineData', 'allUsers'));
}

    public function app()
        {
        // Get today's tasks
        $todayTasks = $this->getTodayTasks();

        // Get calendar days
        $calendarDays = $this->getCalendarDays();

        // Get timeline data
        $timelineData = $this->getTimelineData();

        return view('admin.content', [
            'todayTasks' => $todayTasks,
            'calendarDays' => $calendarDays,
            'timelineData' => $timelineData,
        ]);
    }

    /**
     * Get today's tasks from projects
     */
    // private function getTodayTasks()
    // {
    //     $projects = Project::with(['boards.cards', 'members'])
    //         ->where('created_by', Auth::id())
    //         ->orWhereHas('members', function($q) {
    //             $q->where('user_id', Auth::id());
    //         })
    //         ->get();

    //     $tasks = [];
    //     foreach ($projects as $project) {
    //         foreach ($project->boards as $board) {
    //             foreach ($board->cards->take(2) as $card) {
    //                 $progress = rand(30, 100);
    //                 $tasks[] = [
    //                     'id' => $card->id,
    //                     'title' => $card->title ?? 'Untitled Task',
    //                     'description' => $card->description ?? 'No description',
    //                     'progress' => $progress,
    //                     'members' => ['John', 'Jane', 'Bob'],
    //                     'status' => $card->status ?? 'todo'
    //                 ];
    //             }
    //         }
    //     }

    //     return array_slice($tasks, 0, 2);
    // }

    private function gettodaytasks()
	{
		$projects = Project::with([
			'boards.cards.assignedUsers',   // relasi baru pada Card
			'members'                       // relasi baru pada Project
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

					// ✅ Ambil assigned users, jika ada
					if ($card->assignedUsers->count() > 0) {
						$members = $card->assignedUsers->pluck('full_name')->toArray();
					}
					// ✅ Jika tidak ada assignment → gunakan member project
					else {
						$members = $project->members->pluck('full_name')->toArray();
					}

					$tasks[] = [
						'id' => $card->id,
						'title' => $card->card_title ?? 'Untitled Task',
						'description' => $card->description ?? 'No description',
						'progress' => rand(30, 100),
						'members' => $members,        // ✅ sudah real dari DB
						'status' => $card->status ?? 'todo'
					];
				}
			}
		}

		return array_slice($tasks, 0, 2);
	}

    /**
     * Get calendar days for current month
     */
    private function getCalendarDays()
    {
        $now = now();
        $firstDay = $now->copy()->startOfMonth();
        $lastDay = $now->copy()->endOfMonth();

        $days = [];

        // Add previous month's days
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

        // Add current month's days
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

        // Add next month's days
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

    /**
     * Get timeline data for tasks
     */
    private function getTimelineData()
    {
        return [
            [
                'id' => 'interview',
                'title' => 'Interview',
                'date' => '12',
                'height' => 80,
                'color' => '#FF7F50',
                'color-class' => 'orange-500'
            ],
            [
                'id' => 'ideate',
                'title' => 'Ideate',
                'date' => '13',
                'height' => 65,
                'color' => '#10B981',
                'color-class' => 'teal-500'
            ],
            [
                'id' => 'wireframe',
                'title' => 'Wireframe',
                'date' => '14',
                'height' => 55,
                'color' => '#7C3AED',
                'color-class' => 'purple-500'
            ],
            [
                'id' => 'design',
                'title' => 'Design',
                'date' => '15',
                'height' => 75,
                'color' => '#3B82F6',
                'color-class' => 'blue-500'
            ],
            [
                'id' => 'develop',
                'title' => 'Develop',
                'date' => '16',
                'height' => 90,
                'color' => '#1E293B',
                'color-class' => 'gray-900'
            ],
            [
                'id' => 'test',
                'title' => 'Test',
                'date' => '17',
                'height' => 70,
                'color' => '#10B981',
                'color-class' => 'teal-500'
            ],
            [
                'id' => 'deploy',
                'title' => 'Deploy',
                'date' => '18',
                'height' => 85,
                'color' => '#FF7F50',
                'color-class' => 'orange-500'
            ],
        ];
    }

    public function dash()
    {
        $user = Auth::user();

        // Get all projects where user is creator or member
        $projects = Project::with(['boards.cards', 'members', 'creator'])
            ->where('created_by', $user->id)
            ->orWhereHas('members', function($query) use ($user) {
                $query->where('project_members.user_id', $user->id);
            })
            ->latest()
            ->get();

        return view('admin.dashboard', compact('projects'));
    }

    /**
     * Display the specified project with all boards
     */
    public function showproject(Project $project)
    {
        // Load project with relationships
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
        return view('admin.projects.create');
    }

   public function store(Request $request)
{
    $this->authorizeRole('admin');

    // ✅ VALIDASI yang lebih ketat
    $validated = $request->validate([
        'project_name' => 'required|string|max:255|unique:projects,project_name',
        'description'  => 'nullable|string|max:1000',
        'thumbnail'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        'deadline'     => 'nullable|date|after_or_equal:today',
    ], [
        'project_name.required' => 'Nama project harus diisi',
        'project_name.unique'   => 'Nama project sudah digunakan',
        'thumbnail.image'       => 'File harus berupa gambar',
        'thumbnail.max'         => 'Ukuran gambar maksimal 2MB',
        'deadline.after_or_equal' => 'Deadline tidak boleh di masa lalu',
    ]);

    DB::beginTransaction();

    try {
        // ✅ HANDLE UPLOAD THUMBNAIL dengan nama file yang lebih aman
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '_' . Str::slug($validated['project_name']) . '.' . $file->getClientOriginalExtension();
            $thumbnailPath = $file->storeAs('project-thumbnails', $filename, 'public');
        }

        // ✅ Simpan project dengan data tervalidasi
        $project = Project::create([
            'project_name' => $validated['project_name'],
            'description'  => $validated['description'] ?? null,
            'thumbnail'    => $thumbnailPath,
            'created_by'   => auth()->id(),
            'deadline'     => $validated['deadline'] ?? null,
            'status'       => 'active', // Default status
        ]);

        // ✅ Masukkan creator sebagai super_admin di project_members
        ProjectMember::create([
            'project_id' => $project->id,
            'user_id'    => auth()->id(),
            'role'       => 'super_admin',
            'joined_at'  => now(),
        ]);

        // ✅ (Opsional) Buat board default
        Board::create([
            'project_id' => $project->id,
            'board_name' => 'Main Board',
            'created_by' => auth()->id(),
        ]);

        DB::commit();

        return redirect()
            ->route('admin.allprojects')
            ->with('success', '🎉 Project "' . $project->project_name . '" berhasil dibuat!');

    } catch (\Exception $e) {
        DB::rollBack();

        // ✅ Hapus thumbnail jika rollback terjadi
        if (isset($thumbnailPath) && Storage::disk('public')->exists($thumbnailPath)) {
            Storage::disk('public')->delete($thumbnailPath);
        }

        // ✅ Log error untuk debugging
        \Log::error('Failed to create project: ' . $e->getMessage(), [
            'user_id' => auth()->id(),
            'request' => $request->all()
        ]);

        return redirect()
            ->back()
            ->withInput()
            ->with('error', '❌ Gagal membuat project. Silakan coba lagi.');
    }
}

    public function show($project_id)
    {
        $this->authorizeRole('admin');

        $project = Project::with(['boards.cards', 'members.user'])->findOrFail($project_id);

        // Load relationships
        $project->load(['creator', 'members.user']);

        // Get users who are not already members of this project
        $existingMemberIds = $project->members->pluck('user_id')->toArray();
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

    // ✅ VALIDASI dengan deadline minimal hari ini
    $request->validate([
        'project_name' => 'required|string|max:255',
        'description'  => 'nullable|string',
        'thumbnail'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'deadline'     => 'nullable|date|after_or_equal:today', // ✅ TAMBAHAN INI
    ]);

    try {
        $project = Project::findOrFail($project_id);

        // ✅ HANDLE UPLOAD THUMBNAIL BARU
        if ($request->hasFile('thumbnail')) {
            // Hapus thumbnail lama jika ada
            if ($project->thumbnail) {
                Storage::disk('public')->delete($project->thumbnail);
            }

            // Upload thumbnail baru
            $thumbnailPath = $request->file('thumbnail')->store('project-thumbnails', 'public');

            $project->update([
                'project_name' => $request->project_name,
                'description'  => $request->description,
                'thumbnail'    => $thumbnailPath,
                'deadline'     => $request->deadline,
            ]);
        } else {
            // Update tanpa mengubah thumbnail
            $project->update([
                'project_name' => $request->project_name,
                'description'  => $request->description,
                'deadline'     => $request->deadline,
            ]);
        }

        return redirect()->route('admin.projects.showproject', [$project_id])->with('success', 'Project berhasil diperbarui!');
        // return redirect()->route('admin.monitoring.show', [$project_id])->with('success', 'Project berhasil diperbarui!');

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

            // ✅ HAPUS THUMBNAIL SEBELUM HAPUS PROJECT
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

        $projects = Project::whereHas('members', function ($q) use ($userId) {
            $q->where('user_id', $userId)
              ->whereIn('role', ['admin', 'teamlead']);
        })->with(['boards.cards', 'members.user'])->get();

        // ✅ TAMBAHAN BARU: Ambil semua users untuk team lead dashboard
        $users = User::select('id', 'username', 'full_name', 'email', 'role', 'avatar')
            ->orderBy('full_name')
            ->get();

        // ✅ PERUBAHAN: Tambahkan 'users' ke compact
        return view('teamlead.dashboard', compact('projects', 'users'));
    }

    public function teamLeadShow($project_id)
    {
        $userId = auth()->id();

        $project = Project::whereHas('members', function ($q) use ($userId) {
            $q->where('user_id', $userId)
              ->whereIn('role', ['admin', 'teamlead']);
        })->with(['boards.cards', 'members.user'])->findOrFail($project_id);

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

        return view('designer.dashboard', compact('cards'));
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

    /**
     * ✅ Helper method untuk menentukan status project
     */
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
