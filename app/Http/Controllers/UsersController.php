<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Exceptions\QueryExceptions;
use App\Models\User;
use Illuminate\Database\QueryException;
use App\Traits\ServicesTrait;

class UsersController extends Controller
{
    use ServicesTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return view('users.index', compact('users'));

        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $route = route("users.store");
        return view('users.form',compact('route'));
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $message = 'Registro Creado con exito';
        try {
            $data = $this->fieldsFiles($request->input());
            $data['password'] = bcrypt($data['password']);
            $password = bcrypt($request->password);
            User::create($data);
            $users = User::all();
        } catch (QueryException $th) {
            throw new QueryExceptions($th->getMessage(), $th->getCode());
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
            $route = route("users.update",$id);
            $user = User::findOrFail($id);

        return view('users.form',compact('route','user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $message = 'Registro Actualizado con exito';

        $data = $this->fieldsFiles($request->input());
        if ($data['password'] != null)  // para que revienta y vaya a la exception
            $data['password'] = bcrypt($data['password']);

        $user = User::findOrFail($id);
        $user->update($data);
        $users = User::all();
        return view('users.index',compact('users','message'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
