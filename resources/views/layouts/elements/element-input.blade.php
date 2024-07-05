@extends('layouts.app',['title' => "Ejemplos Input"])
@section('content')
<div class="content" id="content">
    <div class="card cardHeader ">
        <div class="row">
            <div class="col-sm-3">
                <span> Modelo de Ejemplos Inputs </span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                Tipos de Inputs
            </div>
        </div>
        @if (session('message'))
        <div class="bg-yellow mt-1" role="alert">
            <span class="text-danger"> {{ session('message')}} message</span>
        </div>
        @endif
    </div>
    <div class="cardBody ">
        <div class="mt-2 bg-info">
            <div class="col-sm-12 text-center">
                Inputs Bootstrap
            </div>
            <span class="form-label">Texto: </span>
            <input type="text" class="form-control d-inline w-75" placeholder="Form Control texto. Ingrese">
            <br>
            <span class="form-label">Input Number</span>
            <input type="number" class="form-control d-inline w-50"> <br>
        </div>
        <div class="mt-2 bg-info">
            <div class="col-sm-12 text-center">
                Mis Inputs
            </div>
            <span class="form-label">Texto: </span>
            <input type="text" class="form__input w-50 " placeholder="Form Control texto. Ingrese">
            <br>
            <span class="form-label">Input Number</span>
            <input type="number" class="form__input d-inline w-50 mt-3"> <br>
            <span class="form-label">Password</span>
            <input type="password" class="form__input d-inline w-50 mt-3"> <br>
        </div>
    </div>
</div>
@endsection
