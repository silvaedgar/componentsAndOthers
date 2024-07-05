<nav class="navbar navbar-expand-lg navbar-absolute navbar-light bg-info fixed-top shadow">
    <div class="container-fluid">
        <button class="navbar-toggler mb-sm-2 mb-lg-0" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon "></span>
        </button>

        <img class="ms-2 me-3" src="{{asset('image')}}\vesil.png" width="100" height="50" alt="logo">
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <ul class="navbar-nav active">
                <li class="nav-item me-3 ">
                    <a href="{{ route('home-guest') }}" class="my-nav-link">Inicio </a>
                </li>
                <li class="nav-item mb-sm-2 mb-lg-0">
                    <a href="{{ route('about-us') }}" class="my-nav-link">Quienes Somos </a>
                </li>
            </ul>
            <div class="example-spacer"></div>
            <div class="example-spacer"></div>
            <div class="navbar-collapse">
                <ul class="navbar-nav active">
                    <li class="nav-item text-white me-3 mb-sm-2 mb-lg-0">
                        <a href=" {{ route('login') }} " class="my-nav-link">
                            <i class="fas fa-fingerprint"></i> Iniciar Sesión
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a href="{{ route('register') }}" class="my-nav-link">
                            <i class="fa fa-user-plus" aria-hidden="true"></i> Registrarse</a>
                    </li>
                </ul>
            </div>
            {{-- <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a href="{{ route('home-guest') }}" class="nav-link" style="color: white">
                        {{ __('Inicio') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('whoaim') }}" class="nav-link" style="color:white">
                        {{ __('Quienes Somos') }}
                    </a>
                </li>
            </ul> --}}
            {{-- <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="{{ route('register') }}" class="nav-link" style="color: white">
                        <i class="material-icons">person_add</i> {{ __('Registrarse') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('login') }}" class="nav-link" style="color:white">
                        <i class="material-icons">fingerprint</i> {{ __('Login') }}
                    </a>
                </li>
            </ul> --}}
        </div>
    </div>
</nav>

{{-- <nav class="navbar navbar-expand-lg navbar-warning navbar-light bg-info fixed-top shadow">
    <button class="navbar-toggler" data-bs-target="#my-nav" data-bs-toggle="collapse" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div id="my-nav" class="navbar-collapse">
        <ul class="navbar-nav active">
            <li class="nav-item">
                <a href="{{ route('home-guest') }}" class="nav-link1">Inicio </a>
            </li>
            <li class="nav-item ms-3">
                <a href="{{ route('about-us') }}" class="nav-link1">Quienes Somos </a>
            </li>
        </ul>
        <div class="example-spacer"></div>
        <div class="example-spacer"></div>
        <div class="navbar-collapse">
            <ul class="navbar-nav active mr-4">
                <li class="nav-item text-white">
                    <a href=" {{ route('login') }} " class="nav-link1">
                        <i class="fas fa-fingerprint"></i> Iniciar Sesión
                    </a>
                </li>
                <li class="nav-item ms-3">
                    <a href="{{ route('register') }}" class="nav-link1">
                        <i class="fa fa-user-plus" aria-hidden="true"></i> Registrarse</a>
                </li>
            </ul>
        </div>
    </div>

</nav> --}}
