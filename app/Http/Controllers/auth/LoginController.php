<?php

namespace App\Http\Controllers\auth;

use App\Exceptions\QueryExceptions;
use App\Extensions\Utils;
use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class LoginController extends Controller
{
    protected $utils;
    PUBLIC $modulo = "Acceso al Sistema";
    public function __construct(Utils $utils)
    {
         $this->utils = $utils;
    }
    //
    public function index()
    {
        try {
    //        $user = User::where('id',100)->firstOrFail();
            $this->utils->makeLog($this->modulo, "Acceso al formulario de login");
        } catch (\Exception $exception) {
            throw new QueryExceptions($exception,"Error al formulario de login");
        }
        return view('auth.login');

    }

    public function login(Request $request)
    {
        $this->utils->makeLog($this->modulo, "Accedio al modulo de Login", $request->input());
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $userId = auth()->user()->id;
            $isActiveSession = DB::table('sessions')->where('user_id', $userId)->first();
            if ($isActiveSession) {
                $this->utils->makeLog($this->modulo, "Usuario con sesion activa", $request->input());
                auth()->logout();
                session()->invalidate();
                return redirect()->route('login')->with('is_active', true);
            }
            $this->utils->makeLog($this->modulo, "Usuario accede al sistema", $request->input());
            return redirect('home');
        }
        $this->utils->makeLog($this->modulo, "Verificacion de credenciales no valida", $request->input());
        return redirect()
            ->route('login')
            ->with('status', 'Datos de email y/o password invalidos');
    }


    public function register()
    {
        $this->utils->makeLog($this->modulo, "Acceso a la opción de registro de usuario");
        return view('auth.register');
    }

    public function signup(Request $request)
    {
        try {
            //code...
            $this->utils->makeLog("Registro de Usuario","Guardando datos de registro333",$request->input());
            // falta probar en un form request usar el metodo failedvalidator para el log
            $request->validate([
                'name' => 'required|string|max:30',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required',
            ], [
                'name.required' => 'El campo nombre es obligatorio.',
                'name.max' => 'El nombre no puede tener más de :max caracteres.',
                'email.required' => 'El campo correo electrónico es obligatorio.',
                'email.email' => 'El correo electrónico debe ser una dirección válida.',
                'email.unique' => 'El correo electrónico ya está registrado.',
                'password.required' => 'El campo contraseña es obligatorio.',
                'password.min' => 'La contraseña debe tener al menos :min caracteres.',
                'password.confirmed' => 'Las contraseñas no coinciden.',
                'password_confirmation.required' => 'El campo confirmar contraseña es obligatorio.',
            ]);

            info("Usuario registrado: {$request->name}");
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            info("Usuario registrado: {$user->name}");
            $this->utils->makeLog("Registro de Usuario","Datos de registro almacenados con exito",$user);

            return redirect()->route('login')->with('status', "Registro de Usuario exitoso. Ingrese Datos para entrar");
        } catch (\Throwable $th) {
            info("Error al guardar datos de usuario: {$th->getMessage()}");
            $message = "Error al guardar datos del usuario. {$th->getMessage()}. Linea: {$th->getLine()} Error: {$th->getCode()}";
            $this->utils->makeLog("Registro de Usuario",$message, $request->input());
            return redirect()->route('signup')->with('status',"Error guardando datos de usuario: {$th->getMessage()} ");
            //throw $th;
        }
    }

    public function logout(Request $request)
    {
        $this->utils->makeLog($this->modulo, "Cierre de sesion del usuario", auth()->user()->toArray());
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('home');
    }

    public function extendedSession()
    {
        $userId = auth()->user()->id ?? 0;
        $this->utils->makeLog($this->modulo, "Usuario extiende la sesion",$userId);
        return response()->json(['ok' => true]);
    }
}
