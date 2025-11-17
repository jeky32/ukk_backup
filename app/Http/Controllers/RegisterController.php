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
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // buat username unik dari nama
        $base = Str::slug($data['name']);
        $username = $base ?: 'user';
        $i = 1;
        while (User::where('username', $username)->exists()) {
            $username = $base . '-' . $i++;
        }

        $user = User::create([
            'username'  => $username,
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'role'      => 'developer', // default role
            'full_name' => $data['name'],
        ]);

        auth()->login($user);

        // Redirect dengan flash message untuk trigger animasi confetti
        return redirect('/')->with('success', 'Registrasi berhasil! Selamat datang di TIMLY, ' . $user->full_name . '! 🎉');
    }
}
