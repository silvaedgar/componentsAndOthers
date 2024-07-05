@extends('layouts.app',['title' => "Ejemplo Formulario"])

@section('content')
<div class="content" id="content">
    <div class="card cardHeader ">
        <div class="row">
            <div class="col-sm-3">
                <span> Titulo </span>
            </div>
            <div class="col-sm-3">
                <span> SubTitulo </span>
            </div>
            <div class="col-sm-6">
                <button class="btn btn-info float-end mt-2">Boton 1</button>
                <button class="btn btn-info float-end me-3 mt-2">Boton 2</button>
                <button class="btn btn-info float-end me-3 mt-2">Boton 3</button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                Titulo 1

            </div>
            <div class="col-sm-6">
                subtitulo1
            </div>
        </div>
        @if (session('message'))
        <div class="bg-yellow mt-1" role="alert">
            <span class="text-danger"> {{ session('message')}} message</span>
        </div>
        @endif
    </div>
    <div class="cardBody ">
        <div class="mt-2 ">
            <p>Parrafo 1. Lorem ipsum dolor, sit amet consectetur adipisicing elit. A magnam laborum doloremque! Hic
                amet harum
                optio asperiores ab adipisci nesciunt, officiis accusantium dicta qui expedita eos. Numquam alias
                commodi minima?</p>
            <p>Parrafo 2. Lorem ipsum dolor, sit amet consectetur adipisicing elit. A magnam laborum doloremque! Hic
                amet harum
                optio asperiores ab adipisci nesciunt, officiis accusantium dicta qui expedita eos. Numquam alias
                commodi minima?</p>
            <p>Parrafo 3. Lorem ipsum dolor, sit amet consectetur adipisicing elit. A magnam laborum doloremque! Hic
                amet harum
                optio asperiores ab adipisci nesciunt, officiis accusantium dicta qui expedita eos. Numquam alias
                commodi minima?</p>
            {{-- <span class="mt-2">Nombre: </span>
            <input type="text" class="form-control w-25 d-inline">
            <select class="form-select w-25 d-inline">
                <option value="1">Opcion 1</option>
                <option value="2">Opcion 2</option>
                <option value="3">Opcion 3</option>
                <option value="4">Opcion 4</option>
            </select> --}}
        </div>
    </div>
</div>
@endsection
