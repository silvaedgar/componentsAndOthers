@extends('layouts.app')

@section('content')

        @if (isset($message))
            <div class="alert alert-danger mt-2" role="alert">
                {{ $message }}
            </div>
        @endif

<a
    class="btn btn-primary mt-3"
    href="{{ route('users.create') }}"
    role="button"
    >Agregar Usuario</a
>

<div
    class="table-responsive"
>
    <table
        class="table table-primary w-75 mx-auto mt-3"
    >
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Nombre</th>
                <th scope="col">email</th>
                <th scope="col">Acciones</th>

            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr class="">
                    <td scope="row">{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td> <a href="{{ route('users.edit', $user->id) }}">Editar</a>
                    <a href="{{ route('users.destroy', $user->id) }}">Eliminar</a>
                    </td>
                </tr>
            @empty
                <tr colspan = "3"> No hay usuarios</tr>
            @endforelse
        </tbody>
    </table>
</div>


@endsection
