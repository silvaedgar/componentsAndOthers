<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Extensions\Utils;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Traits\ServicesTrait;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    use ServicesTrait;

    protected $utils;
    public $modulo = "Modulo de Usuarios";
    public function __construct(Utils $utils)
    {
        $this->utils = $utils;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        $this->utils->makeLog($this->modulo,"Accedió a la lista de usuarios");

        return view('users.index', compact('users'));

        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->utils->makeLog($this->modulo,"Accedió al Formulario de Crear Usuarios");
        $route = route("users.store");
        return view('users.form',compact('route'));
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            $jsonInput = $this->utils->hideFieldLog($request,['password']);
            $this->utils->makeLog($this->modulo,"Guardando datos de usuario",$jsonInput);
            $message = 'Registro Creado con exito';
            $data = $this->fieldsFiles($request->input());  // ojo imagino que es  para cuandose use un token de clave y no forma parte del user ya que graba data directamente
            $data['password'] = bcrypt($data['password']);
            //$password = bcrypt($request->password);
            User::create($data);
            $this->utils->makeLog($this->modulo,"Usuario creado con exito",$jsonInput);
            $users = User::all();
        } catch (\Throwable $th) {
            $message = "{$th->getMessage()} al grabar datos del usuario: $jsonInput->email.  Linea: {$th->getLine()} Error: {$th->getCode()}";
            $this->utils->makeLog($this->modulo,$message, $jsonInput,null, $message);

            // Y redirigir manualmente
            return redirect()->back()
                ->withInput();
        }
        return view('users.index',compact('users','message'));
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $this->utils->makeLog($this->modulo,"Accedió al Formulario para Editar Usuario: $id");
            $route = route("users.update",$id);
            $user = User::findOrFail($id);
            $this->utils->makeLog($this->modulo,"Consulto datos del Usuario a editar",$user);
            return view('users.form',compact('route','user'));
            //code...
        } catch (\Throwable $th) {
            $message = "{$th->getMessage()} al consultar datos del usuario con ID: $id.  Linea: {$th->getLine()} Error: {$th->getCode()}";
            $this->utils->makeLog($this->modulo,"Error al acceder al formulario de editar usuario",$th->getMessage(), null, $message);
            return redirect()->route('users.index')->with('message', 'Error al acceder al formulario de editar usuario');
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        try {

            $jsonInput = $this->utils->hideFieldLog($request,['password']);
            $this->utils->makeLog($this->modulo,"Actualizando datos de usuario",$jsonInput);
            $data = $this->fieldsFiles($request->input());
            if ($data['password'] != null)  // para que revienta y vaya a la exception
                $data['password'] = Hash::make($data['password']);

            $user = User::findOrFail($id);
            $message = "Registro de usuario {$data['name']} Actualizado con exito";
            $user->update($data);
            $this->utils->makeLog($this->modulo,"Usuario actualizado con exito",$user);
            $users = User::all();
            return view('users.index',compact('users','message'));
        } catch (\Throwable $th) {
            $message = "{$th->getMessage()} al grabar datos del usuario: $jsonInput->email.  Linea: {$th->getLine()} Error: {$th->getCode()}";
            $this->utils->makeLog($this->modulo,"Error al grabar datos de usuario",$jsonInput, null, $message);
            return redirect()->route('users.index')->with('message', "Error grabando datos del usuario {$data['name']}");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
