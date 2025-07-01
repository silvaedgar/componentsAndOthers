<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Extensions\Utils;

class HomeController extends Controller
{
    protected $utils;
    public $modulo = "Pagina de Inicio";

    public function __construct(Utils $utils)
    {
        $this->utils = $utils;
    }

    public function home() {


        $this->utils->makeLog($this->modulo,"Acceso al home de la pagina principal");
        if(auth()->check())
            return view('home');

        return view('menu-not-logged.home');
    }

    public function contact()
    {
        $this->utils->makeLog($this->modulo,"Acceso a la opción contactos de la pagina principal");
        return view('menu-not-logged.contact');
    }

    public function aboutUs()
    {
        $this->utils->makeLog($this->modulo,"Acceso a la opción 'Acerca de' de la pagina principal");
        return view('menu-not-logged.about-us');
    }

}
