<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    // Arahkan user ke halaman login Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Callback setelah login Google
    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Cari user berdasarkan email, kalau tidak ada maka buat
        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'username' => $googleUser->getName(),
                'full_name' => $googleUser->getName(),
                'password' => bcrypt(Str::random(12)),
                'role' => 'developer', // default role
            ]
        );

        // Login user
        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Login Google berhasil!');
    }
}
