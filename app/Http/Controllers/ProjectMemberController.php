<?php

namespace App\Http\Controllers;

use App\Models\ProjectMember;
use App\Models\User;
use App\Models\CardAssignment;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectMemberController extends Controller
{
    /**
     * Tambahkan anggota baru ke proyek
     */
    public function addMember(Request $request, $project_id)
    {
        $project = Project::findOrFail($project_id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:member,admin,super_admin',
        ]);

        // Check if user is already a member
        $existingMember = $project->members()->where('user_id', $request->user_id)->first();

        if ($existingMember) {
            return redirect()
                ->route('admin.projects.show', $project)
                ->with('error', 'User is already a member of this project.');
        }

        // Add new member
        $project->members()->create([
            'user_id' => $request->user_id,
            'role' => $request->role,
            'joined_at' => now(),
        ]);

        return redirect()
            ->route('admin.projects.show', $project->id)
            ->with('success', 'Member added successfully to the project.');

        // // Cari user berdasarkan username
        // $user = User::where('username', $request->username)->first();

        // if (!$user) {
        //     return back()->with('error', '❌ User tidak ditemukan!');
        // }

        // // Cek apakah user masih aktif di project lain
        // $existingMember = ProjectMember::where('user_id', $user->user_id)->first();

        // if ($existingMember) {
        //     // Cek apakah masih ada tugas aktif (assigned/in_progress)
        //     $activeTasks = CardAssignment::where('user_id', $user->user_id)
        //                     ->whereIn('assignment_status', ['assigned', 'in_progress'])
        //                     ->exists();

        //     if ($activeTasks) {
        //         return back()->with('error', '⚠️ User masih punya tugas aktif di proyek lain!');
        //     }

        //     // Kalau tidak ada tugas aktif, maka user bisa dihapus dari project lama (opsional)
        //     // ProjectMember::where('id', $existingMember->id)->delete();
        // }

        // // Tambahkan user ke project baru
        // ProjectMember::create([
        //     'project_id' => $projectId,
        //     'user_id'    => $user->user_id,
        //     'role'       => 'member',
        //     'joined_at'  => now(),
        // ]);

        // return back()->with('success', '✅ Anggota berhasil ditambahkan ke proyek baru!');
    }

    /**
     * Remove a member from the project
     */
    public function destroyMember($projectId, $memberId)
    {
        //$member = $project->members()->findOrFail($memberId);
        $project = Project::findOrFail($projectId);
        $member = ProjectMember::findOrFail($memberId);
// echo "ok"; exit;

        // Optional: Prevent removing the project creator
        if ($member->user_id === $project->created_by) {
            return redirect()
                ->route('admin.projects.show', parameters: $project)
                ->with('error', 'Cannot remove the project creator.');
        }

        $memberName = $member->user->full_name;
        $member->delete();

        return redirect()
            ->route('admin.projects.show', $project->id)
            ->with('success', "Member {$memberName} has been removed from the project.");
    }
}
