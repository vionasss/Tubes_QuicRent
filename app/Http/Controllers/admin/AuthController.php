<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class AuthController extends Controller
{
    public function index()
    {
        return view('admin/index');
    }
    public function showLogin()
    {
        return view('admin/login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.home');
            } else {
                Auth::logout();
                return redirect()->route('login')->withErrors(['email' => 'Access denied.']);
            }
        }

        // Login gagal
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        auth()->logout();  // logout user

        $request->session()->invalidate();  // invalidasi session
        $request->session()->regenerateToken();  // regenerasi CSRF token

        return redirect('/admin/login');  // redirect ke halaman login setelah logout
    }

    public function showRegister()
    {
        return view('admin.register');
    }

    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Login user setelah register
        Auth::login($user);

        // Redirect ke halaman admin jika berhasil login
        return redirect()->route('login');
    }
}

