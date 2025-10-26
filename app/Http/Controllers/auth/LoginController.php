<?php

namespace App\Http\Controllers\auth;

use App\Extensions\Utils;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CryptoController;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    protected $utils;
    private  $modulo = "Acceso al Sistema";
    private  $mensajes = [
        'accesoLogin' => 'Accedió al Login del sistema',
        'sessionActiva' => "Usuario con sesion activa",
        'accesoSistema' => "Usuario accede al sistema",
        'errorCredenciales' => "Verificacion de Credenciales no valida"

    ];

    public function __construct(Utils $utils)
    {
        $this->utils = $utils;
        $this->modulo = config('moduledescription.LoginController');
    }
    //
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $request = CryptoController::dataDecrypt($request->input('data'));
            if ($request == null) throw new \Exception("Error Desencriptando");
            $jsonInput = $this->utils->hideFieldLog($request, ['password', '_token']);
            $this->utils->makeLog($this->modulo, $this->mensajes['accesoLogin'], $jsonInput);
            $messageError = "Credenciales no registradas";
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $userId = auth()->user()->id;
                $isActiveSession = DB::table('sessions')->where('user_id', $userId)->first();
                if ($isActiveSession) {
                    $this->utils->makeLog($this->modulo, $this->mensajes['sessionActiva'], $request->input());
                    auth()->logout();
                    session()->invalidate();
                    return redirect()->route('login')->with('is_active', true);
                }
                $this->utils->makeLog($this->modulo, $this->mensajes['accesoSistema'], $jsonInput);
                return redirect('home');
            }
            //code...
        } catch (\Throwable $th) {
            $message = (isset($jsonInput) ? "al registrar datos del usuario: $jsonInput->email." : "");
            $jsonInput = (isset($jsonInput) ? $jsonInput :  json_encode($request,JSON_PRETTY_PRINT));
            $message = "{$th->getMessage()} $message Linea: {$th->getLine()} Error: {$th->getCode()}";
            $messageError = "Error, no se pudo procesar los datos";
            $this->utils->makeLog($this->modulo,$message, $jsonInput,null, $message);
        }
        $this->utils->makeLog($this->modulo, $this->mensajes['errorCredenciales'], $jsonInput, null, "Credenciales de Usuario no validas");
        return redirect()
            ->back()->withInput()
            ->with('status', $messageError);
    }


    public function register()
    {
        $this->utils->makeLog($this->modulo, "Acceso a la opción de registro de usuario");
        return view('auth.register');
    }

    public function signup(UserRequest $request)
    {
        try {
            $jsonInput = $this->utils->hideFieldLog($request, ['password', '_token', 'password_confirmation']);
            $this->utils->makeLog("Registro de Usuario", "Validando datos de registro", $jsonInput);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' =>  Hash::make($request->password),
            ]);
            $title = (($user) ? "Datos de registro almacenados con exito" :  "Error al guardar datos de registro");

            $this->utils->makeLog("Registro de Usuario", $title, $user, null, $jsonInput);

            if (!$user) return redirect()->route('signup')->with('status', "Error al guardar datos de usuario");

            return redirect()->route('login')->with('status', "Registro de Usuario exitoso. Ingrese Datos para entrar");
        } catch (\Throwable $th) {
            $message = "{$th->getMessage()} al registrar datos del usuario: $jsonInput->email.  Linea: {$th->getLine()} Error: {$th->getCode()}";
            $this->utils->makeLog("Registro de Usuario", $message, $jsonInput, null, $message);
            return redirect()->route('signup')->withInput()->with('status', "Error guardando datos de usuario: {$th->getMessage()} ");
            //throw $th;
        }
    }

    public function logout()
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
        $this->utils->makeLog($this->modulo, "Usuario extiende la sesion", $userId);
        return response()->json(['ok' => true]);
    }
}
