<?php

namespace App\Http\Controllers;

use App\Jobs\ColasJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JobController extends Controller
{
    //
    public function index() {
        $mensaje = "EDGAR";
        ColasJob::dispatch();
        echo $mensaje;
    }
}
