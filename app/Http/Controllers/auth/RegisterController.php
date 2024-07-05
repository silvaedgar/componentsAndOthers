<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    public function store(Request $request) {


        if ($request->password != $request->password1)
            return redirect()->route('register')->with('status',"Las contrasenas no coinciden");

        $request->validate ([
            'name' => 'required|string|max:30',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8'
        ]);

        $user = User::create([
            'name' =>$request->name,
            'email' =>$request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('login')->with('status',"Registro de Usuario exitoso. Ingrese Datos para entrar");
    }
    //
}
