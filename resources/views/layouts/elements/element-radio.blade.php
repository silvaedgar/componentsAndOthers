@extends('layouts.app',['title' => "Ejemplos Radios"])

@section('content')
<div class="content" id="content">
    <div class="card cardHeader ">
        <div class="row">
            <div class="col-sm-3">
                <span> Modelo de Ejemplos con Radios </span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                Tipos de Radios
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
                Radio Bootstrap
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="text-center">
                        <input class="form-check-input ms-3" type="radio" name="flexRadioDefault"
                            id="flexRadioDefault1">
                        Default radio
                        <input class="form-check-input ms-3" type="radio" name="flexRadioDefault" id="flexRadioDefault2"
                            checked>
                        Default checked radio
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                        <label class="form-check-label" for="flexRadioDefault1">
                            Default radio
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2"
                            checked>
                        <label class="form-check-label" for="flexRadioDefault2">
                            Default checked radio
                        </label>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
