<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
            ],
        ]);

        // Verifikasi email dan password
        if (!Auth::validate($credentials)) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password salah.',
                ])
                ->onlyInput('email');
        }

        // Ambil user berdasarkan email
        $user = Auth::getProvider()->retrieveByCredentials([
            'email' => $credentials['email'],
        ]);

        // Pastikan user memiliki role Admin
        if (!$user || $user->role !== 'admin') {
            return back()
                ->withErrors([
                    'email' => 'Akun tidak memiliki akses ke Asset Management.',
                ])
                ->onlyInput('email');
        }

        // Regenerasi session setelah login berhasil
        $request->session()->regenerate();

        // Buat autentikasi khusus Asset Management
        $request->session()->put([
            'asset_management_authenticated' => true,
            'asset_management_user_id' => $user->id,
        ]);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        // Hanya menghapus session Asset Management
        $request->session()->forget([
            'asset_management_authenticated',
            'asset_management_user_id',
        ]);

        return redirect()->route('auth.login');
    }
}