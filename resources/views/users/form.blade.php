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
        <input type="text" class="form-control w-25 d-inline"  name="name" value={{ old('name') == "" ? (isset($user) ? $user->name : "") : old('name') }}>
        @error('name')
            <span class = "text-danger text-sm"> {{ $message }} </span>
        @enderror

    </div>
    <div class="row mt-3">
        <label for="email" class="col-1 text-end">Email</label>
        <input type="email" class="w-25 form-control" name="email" value={{ old('email') == "" ? (isset($user) ? $user->email : "") : old('email') }}>
        @error('email')
            <span class = "text-danger text-sm"> {{ $message }} </span>
        @enderror
    </div>

    <div class="row mt-3">
        <label for="password" class="col-1 text-end">Password</label>
        <input type="password" name="password" class="w-25 form-control" id="" required>
        @error('password')
            <span class = "text-danger text-sm"> {{ $message }} </span>
        @enderror

    </div>

    <div class="row mx-auto mt-3">
        <button type="submit" class="w-25 btn btn-primary">Guardar</button>
        <a href="{{ route('users.index') }}" class="w-25 ms-3 btn btn-danger">Cancelar</a>
    </div>

</form>


@endsection
