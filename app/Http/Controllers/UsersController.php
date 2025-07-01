<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Exceptions\QueryExceptions;
use App\Extensions\Utils;
use App\Models\User;
use Illuminate\Database\QueryException;
use App\Traits\ServicesTrait;

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
        $this->utils->makeLog($this->modulo,"Acceso a la lista de usuarios");

        return view('users.index', compact('users'));

        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->utils->makeLog($this->modulo,"Acceso al Formulario de Crear Usuarios");
        $route = route("users.store");
        return view('users.form',compact('route'));
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->utils->makeLog($this->modulo,"Guardando datos de usuario",$request->input());
        $message = 'Registro Creado con exito';
        try {
            $data = $this->fieldsFiles($request->input());
            $data['password'] = bcrypt($data['password']);
            $password = bcrypt($request->password);
            User::create($data);
            $this->utils->makeLog($this->modulo,"Usuario creado con exito",$data);
            $users = User::all();
        } catch (QueryException $th) {
            throw new QueryExceptions($th, "Guardando datos de Usuario");
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
            $this->utils->makeLog($this->modulo,"Acceso al Formulario de Editar Usuarios","ID de usuario: $id");
            $route = route("users.update",$id);
            $user = User::findOrFail($id);
            return view('users.form',compact('route','user'));
            //code...
        } catch (\Throwable $th) {

            $this->utils->makeLog($this->modulo,"Error al acceder al formulario de editar usuario",$th->getMessage());
            return redirect()->route('users.index',['error' => 'Error al acceder al formulario de editar usuario']);

        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $this->utils->makeLog($this->modulo,"Actualizando datos de usuario",$request->input());
            $message = 'Registro Actualizado con exito';
            $data = $this->fieldsFiles($request->input());
            if ($data['password'] != null)  // para que revienta y vaya a la exception
                $data['password'] = bcrypt($data['password']);

            $user = User::findOrFail($id);
            $user->update($data);
            $this->utils->makeLog($this->modulo,"Usuario actualizado con exito",$user);
            $users = User::all();
            return view('users.index',compact('users','message'));
        } catch (\Throwable $th) {
            info("AQUI");
            throw new QueryExceptions($th, "Actualizando datos de Usuario");
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
