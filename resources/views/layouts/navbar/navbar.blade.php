{{-- <nav class="my__nav ">
    <button class="btn bar"> <i class="fas fa-bars" id="bars"></i>
    </button>
    <span class="ms-4 d-inline-block text-white "> {{ $title }}</span>
    <a data-bs-toggle="collapse" href="#collapseProfile" role="button" aria-expanded="false"
        aria-controls="collapseProfile">
        <span class="float-end me-3 text-white span__profile"> {{ Auth::user()->name}}</span>
        <img src="{{ asset('image')}}/user-profile.jpeg" class="image__profile mt-3" />
    </a>
    <div class="collapse" id="collapseProfile">
        <dl class="collapse__ul-profile text-center">
            <dt><a href="">Mi Perfil</a></dt>
            <form action="{{ route('logout')}}" method="POST">
                @csrf
                <a style="text-decoration: none; color:black; font-weight:bold " href="#" onclick="closeSession(this)">
                    Cerrar
                    Sesion </a>
            </form>
            </ul>
    </div>
</nav> --}}

<nav class="my-3">
    <a href="{{ route('home') }}" class="mx-3"> Home</a>
    <a href="{{ route('users.index') }}" class="mx-3"> Mantenimiento de Usuarios</a>

    <form class="d-inline" action="{{ route('logout')}}" method="POST" >
        @csrf
        <a href="#" onclick="lclick(this)" id="close_session">
            Cerrar Sesion </a>
    </form>
</nav>
