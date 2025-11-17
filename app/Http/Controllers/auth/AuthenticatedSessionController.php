<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses login dan arahkan user sesuai role
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Autentikasi user
        $request->authenticate();
        $request->session()->regenerate();

        // Ambil role user yang login
        $user = Auth::user();

        // Arahkan sesuai role
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'team_lead':
                return redirect()->route('teamlead.dashboard');
            case 'developer':
                return redirect()->route('developer.dashboard');
            case 'designer':
                return redirect()->route('designer.dashboard');
            default:
                Auth::logout();
                return redirect('/login')->withErrors(['role' => 'Role pengguna tidak dikenal.']);
        }
    }

    /**
     * Logout user
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
