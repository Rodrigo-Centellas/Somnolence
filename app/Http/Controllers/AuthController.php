<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function ShowLogin()
    {
        return view('welcome');
    }


    public function login(Request $request)
   {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
 
            return redirect()->intended('dashboard');
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function dashboard(){
        return view('dashboard');
    }

   public function logout(Request $request)
{
    Auth::logout(); // Cierra la sesión

    $request->session()->invalidate(); // Invalida la sesión
    $request->session()->regenerateToken(); // Regenera el token CSRF

    return redirect('/'); // Redirige a la vista de login o bienvenida
}
}
