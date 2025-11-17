<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // buat username unik dari nama
        $base = Str::slug($data['full_name']);
        $username = $base ?: 'user';
        $i = 1;
        while (User::where('username', $username)->exists()) {
            $username = $base . '-' . $i++;
        }

        // logika otomatis tentukan role
        $role = User::count() === 0 ? 'admin' : 'teamlead';

        $user = User::create([
            'username'  => $username,
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'role'      => $role,
            'full_name' => $data['full_name'],
        ]);

        auth()->login($user);

        // arahkan berdasarkan role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Selamat datang Admin!');
        } elseif ($user->role === 'teamlead') {
            return redirect()->route('teamlead.dashboard')->with('success', 'Selamat datang Team Lead!');
        } else {
            return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat!');
        }
    }
}
