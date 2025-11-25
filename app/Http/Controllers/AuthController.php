<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    \Log::info('Intento de login', [
        'email' => $request->email,
        'user_exists' => \App\Models\User::where('email', $request->email)->exists()
    ]);

    if (Auth::attempt($credentials)) {
        $user = Auth::user()->load('role'); 
        
        \Log::info('Login exitoso', [
            'user_id' => $user->id,
            'role' => $user->role ? $user->role->name : 'sin rol'
        ]);

        if ($user->hasRole('Administrador')) {
            return redirect('/admin/restaurants');  //CAmbiado a reestauranres
        } elseif ($user->hasRole('Auditor')) {
            return redirect('/admin/auditor/index');
        } else {
            
            return redirect('/');
        }
    }

    \Log::warning('Credenciales incorrectas', ['email' => $request->email]);
    return back()->withErrors([
        'email' => 'Credenciales incorrectas',
    ]);
}
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}