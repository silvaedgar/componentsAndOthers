<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QueryExceptions extends Exception
{
    public function __construct($message = "Error de base de datos", $code=0, Throwable $previous = null)
    {
        parent::__construct($message,$code,$previous);
    }

    public function render (Request $request) {

        if ($this->code == 23000)
            return view('exceptions.message');
    }
    //
}
