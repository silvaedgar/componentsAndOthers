<aside class="sidebar shadow-lg " id="sidebar">
    <div class="header__sidebar">
        <div class="text-center p-3">
            <img src="{{ asset('image')}}/vesil.png" width="20" height="20" alt="" />
            <span style="font-size: 20px">Vesil C.A</span>
        </div>
        <div class="text-sm ">
            <span style="font-size: 12px;"> Usuario: {{Auth::user()->name}}</span>
            <a href="#" class="float-end mt-1 me-2 cursor-pointer" style="font-size: 12px; ">
                Salir
                <i class="fa-solid fa-right-from-bracket float-end ms-2 me-2" style="margin-top: 5px"></i></a>
        </div>
        <hr>
    </div>

    <div class="container ms-3">
        <a class="sidebar__link" href="home-auth">Home</a>
    </div>

    <div class="container ms-3">
        <a class="sidebar__link" href="form">Forms</a>
    </div>

    <div class="container ms-3">
        <a class="sidebar__link" href="data-table">Data Table</a>
    </div>

    <div class="container ms-3">
        <a class="sidebar__link" data-bs-toggle="collapse" href="#level" role="button" aria-expanded="true"
            aria-controls="level" id="sublevel-1">
            Elementos Form
            <i class="fa fa-caret-up sidebar__caret ms-3" aria-hidden="true" id="caret-sublevel-1"></i>
        </a>

    </div>
    <div class="collapse" id="level">
        <ul class="sidebar__dropdown">
            <li> <a href="element-input" class="sidebar__dropdown-link">Input</a></li>
            <li> <a href="element-select" class="sidebar__dropdown-link">Select</a></li>
            <li> <a href="element-check" class="sidebar__dropdown-link">Check</a></li>
            <li> <a href="element-radio" class="sidebar__dropdown-link">Radio</a></li>
            <li> <a href="element-button" class="sidebar__dropdown-link">Buttons</a></li>
        </ul>
    </div>

</aside>
