@extends('layouts.app')

@section('content')

    <div class="container w-25 mt-5 rounded shadow ">
        @if (session('status'))
            <div class="alert alert-danger mt-2" role="alert">
                {{ session('status') }}
            </div>
        @endif


        <div class="row">
            <div class="text-center mt-3">
                <h4> Inicio de Sesion</h4>
            </div>
            <form method="POST" action = "{{ route('login')}}">
                @csrf
                <div class="mb-2">
                    <label for="email" class="form-label" > Correo Electronico</label>
                    <div class="form-group has-feedback">
                        <input type="email" class="form-control" value = "{{ old('email') }}" required name="email" >
                    </div>
                </div>
                <div class="mb-2">
                    <label for="password" class="form-label"> Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                {{-- <div class="mb-1">
                            <input type="checkbox" class="form-check-input" >
                            <label class="form-check-label">Mantenerme Conectado</label>
                </div> --}}
                <div class="d-grid">
                    <button type= "submit" class="btn btn-primary">Iniciar Sesion </button>
                </div>
                <div class="row mt-2 my-2 justify-content-start ">
                            {{-- <div class="col-4"><a class= "" href=""> Registrarme </a> </div>
                    <div class="col-8 text-end"> --}}
                        <a href="" style="font-size: small"> Olvidaste la Clave?. Recuperala </a>
                    {{-- </div> --}}
                </div>
            </form>

        </div>
    </div>
@endsection

@push('js')

<script>

    document.addEventListener('DOMContentLoaded', function () {

        let is_session_active = "{{ session('is_active') }}"
        if (is_session_active) {
            alert("Usuario tiene una sesion activa");
        }

    })
</script>

@endpush








