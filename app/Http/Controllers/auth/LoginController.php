<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class LoginController extends Controller
{
    //
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $userId = auth()->user()->id;
            $isActiveSession = DB::table('sessions')->where('user_id', $userId)->first();
            if ($isActiveSession) {
                auth()->logout();
                session()->invalidate();
                return redirect()->route('login')->with('is_active', true);
            }
            return redirect('home');
        }

        return redirect()
            ->route('login')
            ->with('status', 'Datos de email y/o password invalidos');
    }


    public function register()
    {
        return view('auth.register');
    }

    public function signup(Request $request)
    {
        if ($request->password != $request->password1)
            return redirect()->route('signup')->with('status', "Las contrasenas no coinciden");

        $request->validate([
            'name' => 'required|string|max:30',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
            // 'password_confirmation' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('login')->with('status', "Registro de Usuario exitoso. Ingrese Datos para entrar");
    }

    public function logout(Request $request)
    {
        Log::info("LOGOUT ", [$request]);
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('home');
    }

    public function extendedSession()
    {
        return response()->json(['ok' => true]);
    }
}
