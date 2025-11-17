<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\DesignerController;
use App\Http\Controllers\TeamLeadController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PanelMemberController;
use App\Http\Controllers\PanelTeamLeadController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\ActivityController;
use Illuminate\Support\Facades\Hash;

// ===================================================
// 🏠 LANDING PAGE - ROOT ROUTE (/)
// ===================================================
Route::get('/', function () {
    // Jika sudah login, redirect ke dashboard sesuai role
    if (auth()->check()) {
        $user = auth()->user();
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'teamlead':
                return redirect()->route('teamlead.dashboard');
            case 'developer':
            case 'designer':
                return redirect()->route('developer.dashboard');
            default:
                return redirect()->route('dashboard');
        }
    }
    // Jika belum login, tampilkan landing page
    return view('welcome');
})->name('landing');

// ===================================================
// 🏠 LANDING PAGE ALTERNATIVE - /HOME
// ===================================================
Route::get('/home', function () {
    // Jika sudah login, redirect ke dashboard sesuai role
    if (auth()->check()) {
        $user = auth()->user();
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'teamlead':
                return redirect()->route('teamlead.dashboard');
            case 'developer':
            case 'designer':
                return redirect()->route('developer.dashboard');
            default:
                return redirect()->route('dashboard');
        }
    }
    // Jika belum login, tampilkan landing page
    return view('welcome');
})->name('home');

// ===================================================
// 🧪 TEST ROUTES (Sementara untuk debug - bisa dihapus nanti)
// ===================================================
Route::get('/make-password/{text}', function ($text) {
    return Hash::make($text);
});

// ===================================================
// 🔐 AUTH ROUTES
// ===================================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// REGISTER & FORGOT PASSWORD
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// LOGIN VIA GOOGLE
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);
Route::get('/admin/projects/{project_id}', [ProjectController::class, 'show'])->name('admin.projects.show');
Route::get('admin/project/create', [ProjectController::class, 'create'])->name('admin.projects.create');
Route::post('/admin/boards/', [BoardController::class, 'store'])->name('admin.boards.store');

// API Time Logs Calendar
Route::get('/api/time-logs-calendar', [ProjectController::class, 'getTimeLogsCalendar'])
    ->name('api.timelogs.calendar')
    ->middleware('auth');

Route::middleware(['auth'])->prefix('panel/member')->name('panel.member.')->group(function () {
    // Panel utama member
    Route::get('/project/{projectId}', [PanelMemberController::class, 'index'])->name('index');

    // Card actions
    Route::post('/card/{cardId}/start', [PanelMemberController::class, 'startWork'])->name('start');
    Route::post('/card/{cardId}/stop', [PanelMemberController::class, 'stopWork'])->name('stop');
    Route::post('/card/{cardId}/review', [PanelMemberController::class, 'requestReview'])->name('review');
    Route::post('/card/{cardId}/blocker', [PanelMemberController::class, 'requestBlocker'])->name('blocker');

    // Comment
    Route::post('/card/{cardId}/comment', [PanelMemberController::class, 'addComment'])->name('comment');
});

/*
|--------------------------------------------------------------------------
| Panel TeamLead Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:teamlead'])->prefix('teamlead')->name('teamlead.')->group(function () {

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // 📊 DASHBOARD
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    Route::get('/dashboard', [TeamLeadController::class, 'dashboard'])->name('dashboard');

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // 📁 PROJECTS MANAGEMENT
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    Route::get('/projects', [TeamLeadController::class, 'projects'])->name('projects.index');
    Route::get('/projects/{project}', [TeamLeadController::class, 'showProject'])->name('projects.show');
    Route::get('/project-detail/{project}', [TeamLeadController::class, 'projectDetail'])->name('project.detail');

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // 📈 MONITORING
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    Route::get('/monitoring', [TeamLeadController::class, 'monitoring'])->name('monitoring');

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // 📊 REPORTS
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    Route::get('/reports', [TeamLeadController::class, 'reports'])->name('reports.index');
    Route::get('/reports/{project}', [TeamLeadController::class, 'showReport'])->name('reports.show');
    Route::get('/team-report', [TeamLeadController::class, 'teamReport'])->name('team.report');

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // 💬 MESSAGES
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    Route::get('/messages', [TeamLeadController::class, 'messages'])->name('messages');

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // ✅ REVIEW & APPROVAL
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    Route::get('/review', [TeamLeadController::class, 'review'])->name('review');

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // 🕑 HISTORY / RIWAYAT
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    Route::get('/riwayat', [TeamLeadController::class, 'riwayat'])->name('riwayat.index');


    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // 📋 TASK ASSIGNMENTS
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    Route::get('/task-assignments', [TeamLeadController::class, 'taskAssignments'])->name('task.assignments');
    Route::post('/assign-task/{card}', [TeamLeadController::class, 'assignTask'])->name('task.assign');
    Route::delete('/remove-assignment/{card}/{user}', [TeamLeadController::class, 'removeAssignment'])->name('assignment.remove');

});

/*
|--------------------------------------------------------------------------
| 🎛️ TEAM LEAD PANEL ROUTES (Kanban Board Management)
|--------------------------------------------------------------------------
| Routes untuk manage cards, status, comments di project panel
| Middleware: auth
*/

Route::middleware(['auth', 'role:teamlead'])->prefix('teamlead')->name('teamlead.')->group(function () {
    Route::get('/messages', [TeamLeadController::class, 'messages'])->name('messages');
    Route::get('/messages/chat/{userId}', [TeamLeadController::class, 'loadChat'])->name('messages.load-chat');
    Route::post('/messages/send', [TeamLeadController::class, 'sendMessage'])->name('messages.send');
    Route::get('/messages/unread-count', [TeamLeadController::class, 'getUnreadCount'])->name('messages.unread-count');
    Route::get('/messages/search-users', [TeamLeadController::class, 'searchUsers'])->name('messages.search-users');
    Route::get('/review/{card}/detail', [TeamLeadController::class, 'reviewDetail'])->name('review.detail');
    Route::get('/history', [TeamLeadController::class, 'riwayat'])->name('history');
    Route::get('/card/{card}/detail', [TeamLeadController::class, 'getCardDetail'])->name('card.detail');
    Route::get('/history/export', [TeamLeadController::class, 'exportHistory'])->name('history.export');
});

Route::post('/developer/cards/{card}/block', [DeveloperController::class, 'blockTask'])
    ->name('developer.cards.block');

Route::middleware(['auth'])->prefix('panel/teamlead')->name('panel.teamlead.')->group(function () {

    // Panel utama teamlead (Kanban Board)
    Route::get('/project/{projectId}', [PanelTeamLeadController::class, 'index'])->name('index');

    // Card status management
    Route::post('/card/{cardId}/status', [PanelTeamLeadController::class, 'changeStatus'])->name('status');

    // Comment on card
    Route::post('/card/{cardId}/comment', [PanelTeamLeadController::class, 'addComment'])->name('comment');

    // Delete comment (optional)
    Route::delete('/comment/{commentId}', [PanelTeamLeadController::class, 'deleteComment'])->name('comment.delete');

    // Assign/Unassign user to card (optional)
    Route::post('/card/{cardId}/assign', [PanelTeamLeadController::class, 'assignUser'])->name('card.assign');
    Route::delete('/card/{cardId}/unassign/{userId}', [PanelTeamLeadController::class, 'unassignUser'])->name('card.unassign');

});

/*
|--------------------------------------------------------------------------
| 🔗 ADDITIONAL TEAM LEAD ROUTES (Optional)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:teamlead'])->group(function () {

    // Quick actions
    Route::post('/teamlead/quick-assign/{card}/{user}', [TeamLeadController::class, 'quickAssign'])->name('teamlead.quick.assign');
    Route::post('/teamlead/approve-task/{card}', [TeamLeadController::class, 'approveTask'])->name('teamlead.approve.task');
    Route::post('/teamlead/reject-task/{card}', [TeamLeadController::class, 'rejectTask'])->name('teamlead.reject.task');

});
// ===================================================
// 👥 PROTECTED ROUTES (User harus login)
// ===================================================
Route::middleware('auth')->group(function () {
    // === DASHBOARD UMUM ===
    Route::get('/dashboard', [ProjectController::class, 'index'])->name('dashboard');

    // === REPORTS ===
    Route::get('/teamlead/reports', [ReportController::class, 'index'])->name('teamlead.reports.index');
    Route::post('/teamlead/reports/export-project', [ReportController::class, 'exportProjectReport'])->name('teamlead.reports.export-project');
    Route::post('/teamlead/reports/export-team', [ReportController::class, 'exportTeamReport'])->name('teamlead.reports.export-team');
    Route::post('/teamlead/reports/export-comprehensive', [ReportController::class, 'exportComprehensiveReport'])->name('teamlead.reports.export-comprehensive');
    Route::get('/teamlead/projects/{project_id}', [ProjectController::class, 'teamLeadShow'])->name('teamlead.projects.show');

    // === PROJECT MANAGEMENT ===
    Route::get('/manajemen_projects', [ProjectController::class, 'manajemen_projects'])->name('manajemen_projects');
    // Route::get('/admin/projects/create', [ProjectController::class, 'create'])->name('admin.projects.create');
    Route::post('/admin/projects', [ProjectController::class, 'store'])->name('admin.projects.store');
    Route::get('/admin/projects/{project_id}', [ProjectController::class, 'show'])->name('admin.projects.show');
    Route::get('/admin/projects/{project_id}/edit', [ProjectController::class, 'edit'])->name('admin.projects.edit');
    Route::put('/admin/projects/{project_id}', [ProjectController::class, 'update'])->name('admin.projects.update');
    Route::delete('/admin/projects/{project_id}', [ProjectController::class, 'destroy'])->name('admin.projects.destroy');

    // ✅ DELETE THUMBNAIL
    Route::delete('/admin/projects/{project_id}/thumbnail', [ProjectController::class, 'deleteThumbnail'])->name('admin.projects.thumbnail.delete');

    // === PROJECT MEMBERS ===
    Route::post('admin/projects/{project_id}/members', [ProjectMemberController::class, 'addMember'])->name('admin.projects.members.add');
    Route::delete('admin/projects/{project_id}/members/{member_id}', [ProjectMemberController::class, 'destroyMember'])->name('admin.projects.members.destroy');

    // === PROJECT VIEWS ===
    Route::get('admin/dash', [ProjectController::class, 'dash'])->name('dash');
    Route::get('admin/app', [ProjectController::class, 'app'])->name('app');
    Route::get('admin/allprojects', [ProjectController::class, 'allprojects'])->name('admin.allprojects');
    Route::get('/admin/projects/show/{project}', [ProjectController::class, 'showproject'])->name('admin.projects.showproject');
    Route::get('admin/projects/{project}/boards/{board}', [BoardController::class, 'show'])->name('admin.boards.show');

    // === CARDS MANAGEMENT ===
    Route::post('/admin/cards', [CardController::class, 'store'])->name('admin.cards.store');
    Route::get('/admin/cards/{card}', [CardController::class, 'show'])->name('admin.cards.show');
    Route::put('/admin/cards/{card}', [CardController::class, 'update'])->name('admin.cards.update');
    Route::delete('/admin/cards/{card}', [CardController::class, 'destroy'])->name('admin.cards.destroy');

    // === CARD ACTIONS ===
    Route::post('/admin/cards/{card}/status', [CardController::class, 'updateStatus'])->name('admin.cards.status');
    Route::post('/admin/cards/{card}/position', [CardController::class, 'updatePosition'])->name('admin.cards.position');
    Route::post('/admin/cards/{card}/comments', [CardController::class, 'addComment'])->name('admin.cards.comments.store');
    Route::post('/admin/cards/{card}/duplicate', [CardController::class, 'duplicate'])->name('admin.cards.duplicate');

    // === WORK STATUS ===
    Route::post('/admin/cards/{card}/start-work', [CardController::class, 'startWork'])->name('admin.cards.start-work');
    Route::post('/admin/cards/{card}/complete-work', [CardController::class, 'completeWork'])->name('admin.cards.complete-work');

    // === TIME LOGGING ===
    Route::post('/cards/{card}/log-time', [CardController::class, 'logTime'])->name('admin.cards.log-time');

    // === SUBTASKS (Tasks/Checklist in Cards) ===
    Route::post('/admin/cards/{card}/tasks', [CardController::class, 'addTask'])->name('admin.cards.tasks.store');
    Route::put('/admin/cards/{card}/tasks/{task}', [CardController::class, 'updateTask'])->name('admin.cards.tasks.update');
    Route::delete('/admin/cards/{card}/tasks/{task}', [CardController::class, 'deleteTask'])->name('admin.cards.tasks.destroy');

    // === DEVELOPER ROUTES ===
Route::middleware(['auth'])->prefix('developer')->name('developer.')->group(function () {
    Route::get('/dashboard', [DeveloperController::class, 'dashboard'])->name('dashboard');
    
    // Task actions
    Route::post('/tasks/{card}/start', [DeveloperController::class, 'startTask'])->name('start-task');
    Route::post('/tasks/pause', [DeveloperController::class, 'pauseTask'])->name('pause-task');
    Route::post('/tasks/{card}/complete', [DeveloperController::class, 'completeTask'])->name('complete-task');
    Route::post('/cards/{card}/block', [DeveloperController::class, 'blockCard'])->name('cards.block');
    
    // Time logs & statistics
    Route::get('/time-logs', [DeveloperController::class, 'timeLogs'])->name('time-logs');
    Route::get('/statistics', [DeveloperController::class, 'statistics'])->name('statistics');
    
    // **ROUTE BARU: View Board untuk Developer (Read-only)**
    Route::get('/projects/{project}/boards/{board}', [DeveloperController::class, 'showBoard'])->name('boards.show');
});


// === CARD COMMENT ROUTE (jika belum ada) ===
Route::post('/cards/{card}/comment', [CardController::class, 'comment'])->name('cards.comment');

    // === DESIGNER ROUTES ===
    Route::get('/designer/dashboard', [DesignerController::class, 'dashboard'])->name('designer.dashboard');
    Route::post('/designer/task/{card}/start', [DesignerController::class, 'startTask'])->name('designer.task.start');
    Route::post('/designer/task/pause', [DesignerController::class, 'pauseTask'])->name('designer.task.pause');
    Route::post('/designer/task/{card}/complete', [DesignerController::class, 'completeTask'])->name('designer.task.complete');
    Route::get('/designer/time-logs', [DesignerController::class, 'timeLogs'])->name('designer.time-logs');
    Route::get('/designer/statistics', [DesignerController::class, 'statistics'])->name('designer.statistics');

    // ===================================================
    // 🛠️ ADMIN ROUTES
    // ===================================================
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        // Dashboard Admin
        Route::get('/dashboard', [ProjectController::class, 'index'])->name('dashboard');

        // === USER MANAGEMENT ROUTES ===
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // === PROJECT ROUTES ===
        Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');

        // === MONITORING PROJECT ===
        Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
        Route::get('/monitoring/{project}', [MonitoringController::class, 'show'])->name('monitoring.show');

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // ⚙️ SETTINGS ROUTES - COMPLETE & FULLY FUNCTIONAL
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
        Route::post('/settings/preferences', [SettingsController::class, 'updatePreferences'])->name('settings.preferences');
        Route::post('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications');
        Route::post('/settings/privacy', [SettingsController::class, 'updatePrivacy'])->name('settings.privacy');
        Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
        Route::post('/settings/reset', [SettingsController::class, 'resetSettings'])->name('settings.reset');
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // 📋 TASKS ROUTES - NEW FEATURE
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
        Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
        Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        // 📊 ACTIVITIES ROUTES - NEW FEATURE
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
        Route::get('/activities/project/{projectId}', [ActivityController::class, 'forProject'])->name('activities.project');
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    });

    // ===================================================
    // 👨‍💼 TEAM LEAD ROUTES
    // ===================================================
    Route::middleware('role:teamlead')->group(function () {
        Route::get('/teamlead/dashboard', [TeamLeadController::class, 'dashboard'])->name('teamlead.dashboard');

        // Cards
        Route::get('/teamlead/boards/{board}/cards', [CardController::class, 'index'])->name('teamlead.cards.index');
        Route::get('/teamlead/boards/{board}/cards/create', [CardController::class, 'create'])->name('teamlead.cards.create');
        Route::post('/teamlead/boards/{board}/cards', [CardController::class, 'store'])->name('teamlead.cards.store');
        Route::get('/teamlead/boards/{board}/cards/{card}/edit', [CardController::class, 'edit'])->name('teamlead.cards.edit');
        Route::put('/teamlead/boards/{board}/cards/{card}', [CardController::class, 'update'])->name('teamlead.cards.update');
        Route::delete('/teamlead/boards/{board}/cards/{card}', [CardController::class, 'destroy'])->name('teamlead.cards.destroy');

        // Subtask Approval
        Route::post('/subtasks/{subtask}/approve', [SubtaskController::class, 'approve'])->name('subtasks.approve');
        Route::post('/subtasks/{subtask}/reject', [SubtaskController::class, 'reject'])->name('subtasks.reject');
    });

    // ===================================================
    // 💻 DEVELOPER & DESIGNER ROUTES
    // ===================================================
    Route::middleware('role:developer,designer')->group(function () {
        // Subtasks
        Route::get('/cards/{card}/subtasks/create', [SubtaskController::class, 'create'])->name('subtasks.create');
        Route::post('/cards/{card}/subtasks', [SubtaskController::class, 'store'])->name('subtasks.store');
        Route::post('/subtasks/{subtask}/start', [SubtaskController::class, 'start'])->name('subtasks.start');
        Route::post('/subtasks/{subtask}/complete', [SubtaskController::class, 'complete'])->name('subtasks.complete');
    });
});
