@extends('layouts.app')

@section('content')

<form action="{{ $route }}" method="post" class="mt-4">
    @csrf

    @if (isset($user))
        @method('PUT')
    @endif

    <input type="hidden" name="id" value = {{ isset($user) ? $user->id : 0  }}>

    <div class="row">
        <label for="name" class="col-1 text-end">Nombre: </label>
        <input type="text" class="form-control w-25 d-inline"  name="name" value={{ isset($user) ? $user->name : '' }}>
    </div>
    <div class="row mt-3">
        <label for="email" class="col-1 text-end">Email</label>
        <input type="email" class="w-25 form-control" name="email" value={{ isset($user) ? $user->email : '' }}>
    </div>

    <div class="row mt-3">
        <label for="password" class="col-1 text-end">Password</label>
        <input type="password" name="password" class="w-25 form-control" id="">
    </div>

    <div class="row mx-auto mt-3">
        <button type="submit" class="w-25 btn btn-primary">Crear</button>
        <a href="{{ route('users.index') }}" class="w-25 ms-3 btn btn-danger">Cancelar</a>
    </div>

</form>


@endsection
