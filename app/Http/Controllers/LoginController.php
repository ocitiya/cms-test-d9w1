<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller{
    public function login(Request $request): HttpResponse {
        return response()->view('login');
    }

    public function authenticate(Request $request): RedirectResponse {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Silahkan masukkan email Anda',
            'email.email' => 'Alamat email tidak valid',
            'password.required' => 'Silahkan masukkan password Anda'
        ]);
 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
 
            return redirect()->intended(route('product.list'));
        }
 
        return back()->withErrors([
            'credentials' => 'email atau password Anda tidak ditemukan!',
        ])->onlyInput('email');
    }

    public function onLogout(Request $request): RedirectResponse {
        Auth::logout();
        $request->session()->regenerate();

        return redirect()->route('login');
    }
}
