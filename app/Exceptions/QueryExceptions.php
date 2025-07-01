<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;


class QueryExceptions extends Exception
{
    private $description;
    //public function __construct(Exception $exception,$message = "Accediendo a la aplicacion")
    public function __construct(Exception $exception,$description = "Error de acceso a la aplicacion")
    {

        $this->description = $description;
        parent::__construct($exception->getMessage(),$this->code);
        //parent::__construct($exception,$message);
    }

    public function render (Request $request) {
        info("Exception en QueryExceptions: {$this->code} {$this->message} ");
        $utils = new \App\Extensions\Utils();
        $utils->makeLog("QueryExceptions ", $this->description, substr($this->message,0,200));
//        return view('exceptions.message');
    }
    //
}
