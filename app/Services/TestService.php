<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class TestService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function procesarFilesPendientes() {

        Log::info("ESTOY EN EL SERVICIO");
    }
}
