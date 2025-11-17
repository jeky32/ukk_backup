<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // ==========================================================
    // 🔹 SHOW LOGIN
    // ==========================================================
    public function showLogin()
    {
        // ✅ Jika sudah login, redirect ke dashboard sesuai role
        if (auth()->check()) {
            return $this->redirectByRole(auth()->user());
        }

        return view('auth.login');
    }

    // ==========================================================
    // 🔹 LOGIN
    // ==========================================================
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginField = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where($loginField, $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.'])->withInput();
        }

        // 🔐 Cek password
        if (!Hash::check($request->password, $user->password)) {
            // Auto-hash untuk password plaintext (jika ada)
            if ($user->password === $request->password) {
                $user->password = Hash::make($request->password);
                $user->save();
            } else {
                return back()->withErrors(['password' => 'Password salah.'])->withInput();
            }
        }

        // ✅ Login berhasil
        Auth::login($user);
        $request->session()->regenerate();

        session([
            'user_id'   => $user->id,
            'role'      => $user->role,
            'username'  => $user->username,
            'full_name' => $user->full_name ?? $user->username,
        ]);

        return $this->redirectByRole($user);
    }

    // ==========================================================
    // 🔹 SHOW REGISTER
    // ==========================================================
    public function showRegister()
    {
        // ✅ Jika sudah login, redirect ke dashboard sesuai role
        if (auth()->check()) {
            return $this->redirectByRole(auth()->user());
        }

        return view('auth.register');
    }

    // ==========================================================
    // 🔹 REGISTER
    // ==========================================================
    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users,email',
            'password'  => 'required|string|min:6|confirmed',
        ]);

        // 🔹 Buat username unik otomatis
        $baseUsername = Str::slug($request->full_name);
        $username = $baseUsername ?: 'user';
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter++;
        }

        // ✅✅ SESUAI TUGAS: User pertama = admin, sisanya = developer ✅✅
        $userCount = User::count();

        if ($userCount === 0) {
            // User pertama otomatis jadi admin
            $role = 'admin';
        } else {
            // User kedua dan seterusnya jadi developer
            $role = 'developer';
        }

        // 🔹 Simpan user ke database
        $user = User::create([
            'username'  => $username,
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $role,
            'current_task_status' => 'idle',
        ]);

        // 🔹 Login otomatis setelah register
        Auth::login($user);

        // 🔹 Redirect sesuai role dengan animasi confetti 🎉
        return $this->redirectByRole($user, true);
    }

    // ==========================================================
    // 🔹 LOGOUT
    // ==========================================================
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // ✅ Redirect ke landing page setelah logout
        return redirect()->route('home')->with('success', 'Berhasil logout!');
    }

    // ==========================================================
    // 🔹 Fungsi bantu: Redirect berdasarkan role
    // ==========================================================
    private function redirectByRole($user, $isRegister = false)
    {
        // ✅ Pesan dengan emoji untuk trigger animasi confetti
        $message = $isRegister
            ? "Registrasi berhasil! Selamat datang di TIMLY, {$user->full_name}! 🎉"
            : "Selamat datang kembali, {$user->full_name}!";

        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard')->with('success', $message);

            case 'teamlead':
                return redirect()->route('teamlead.dashboard')->with('success', $message);

            case 'developer':
                  return redirect()->route('developer.dashboard')->with('success', $message);

            case 'designer':
                return redirect()->route('designer.dashboard')->with('success', $message);

            default:
                return redirect()->route('developer.dashboard')->with('success', $message);
        }
    }
}
