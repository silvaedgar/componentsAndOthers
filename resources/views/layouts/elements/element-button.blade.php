@extends('layouts.app',['title' => "Ejemplos Buttons"])

@section('content')
<div class="content" id="content">
    <div class="card cardHeader ">
        <div class="row">
            <div class="col-sm-3">
                <span> Modelo de Ejemplos Buttons </span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                Tipos de Buttons
            </div>
        </div>
        @if (session('message'))
        <div class="bg-yellow mt-1" role="alert">
            <span class="text-danger"> {{ session('message')}} message</span>
        </div>
        @endif
    </div>
    <div class="cardBody ">
        <div class="mt-2 bg-yellow">
            <div class="col-sm-12 text-center">
                Botones Bootstrap
            </div>
            <input type="button" class="col-sm-2 btn btn-primary ms-4" value="Boton Primary">
            <input type="button" class="col-sm-2 btn btn-danger" value="Boton Danger">
            <input type="button" class="col-sm-2 btn btn-warning" value="Boton Warning">
            <input type="button" class="col-sm-2 btn btn-success" value="Boton Success">
            <input type="button" class="col-sm-2 btn btn-secondary" value="Boton Secondary">
            <input type="button" class="col-sm-1 btn btn-info" value="Boton Info">
        </div>
        <div class="mt-2 bg-info">
            <div class="col-sm-12 text-center">
                Mis Backgrounds
            </div>
            <input type="button" class="col-sm-2 btn bg-red ms-4" value="bg-red">
            <input type="button" class="col-sm-2 btn bg-yellow" value="bg-yellow">
            <input type="button" class="col-sm-2 btn bg-purple" value="bg-purple">
            <input type="button" class="col-sm-2 btn bg-cyan" value="bg-cyan">
            <input type="button" class="col-sm-2 btn bg-green" value="bg-green">
            <input type="button" class="col-sm-1 btn bg-blue" value="bg-blue">
        </div>
    </div>
</div>
@endsection
