<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function home() {

        Log::info("HOME CONTROLLER");
        if(auth()->check())
            return view('home');

        return view('menu-not-logged.home');
    }

    public function contact()
    {
        Log::info("CONATC  CONTROLLER");
        return view('menu-not-logged.contact');
        // return "EDGAR";
    }

    public function aboutUs()
    {
        return view('menu-not-logged.about-us');
    }

}
