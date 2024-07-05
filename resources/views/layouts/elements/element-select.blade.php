@extends('layouts.app',['title' => "Ejemplos Select"])

@section('content')
<div class="content" id="content">
    <div class="card cardHeader ">
        <div class="row">
            <div class="col-sm-3">
                <span> Modelo de Ejemplos Selects </span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                Tipos de Select
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
                Select Bootstrap
            </div>
            <span class="form-label">Select con form-select: </span>
            <select class="form-select d-inline w-75 mb-2">
                <option value="1">Select one</option>
                <option value="2">Select two</option>
                <option value="3">Select three</option>
                <option value="4">Select four</option>
            </select>
            <span class="form-label">Select con form-control: </span>
            <select class="form-control d-inline w-75 mb-2">
                <option value="1">Select one</option>
                <option value="2">Select two</option>
                <option value="3">Select three</option>
                <option value="4">Select four</option>
            </select>
        </div>
        <div class="mt-2 bg-info">
            <div class="col-sm-12 text-center">
                Mis Selects
            </div>
            <span class="form-label">Select con form-select: </span>
            <select class="form__select d-inline w-75 mb-2">
                <option value="1">Select one</option>
                <option value="2">Select two</option>
                <option value="3">Select three</option>
                <option value="4">Select four</option>
            </select> <br>
            <span class="form-label">Select con form-control: </span>
            <select class="form__input d-inline w-75 mb-2">
                <option value="1">Select one</option>
                <option value="2">Select two</option>
                <option value="3">Select three</option>
                <option value="4">Select four</option>
            </select>
        </div>
    </div>
</div>
@endsection
