<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Extensions\Utils;

class RegisterController extends Controller
{
    protected $utils;
    public function __construct(Utils $utils)
    {
        $this->utils = $utils;
    }
    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request) {
        try {
            $this->utils->makeLog("Registro de Usuario","Guardando datos de registro11",$request->input());

            if ($request->password != $request->password1) {
                $this->utils->makeLog("Registro de Usuario","Error Contraseñas no coinciden ",$request->input());
                return redirect()->route('register')->with('status',"Las contrasenas no coinciden");
            }

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
            $this->utils->makeLog("Registro de Usuario","Acceso a la pagina de registro","Datos de usuario guardados",$request->input(),$user);

            return redirect()->route('login')->with('status',"Registro de Usuario exitoso. Ingrese Datos para entrar");
        } catch (\Throwable $th) {
            $message = "Error al guardar datos del usuario. {$th->getMessage()}. Linea: {$th->getLine()}";
            $this->utils->makeLog("Registro de Usuario","Acceso a la pagina de registro",$message,$request->input());
        }
    }
    //
}
