<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];
    //

    protected $dontFlash = ['password', 'password_confirmation'];

    public function report(Throwable $exception) {
        parent::report($exception);
    }

    public function render($request, Throwable $exception) {
        log::info("ENTRE AL EXCEPTION");
        return parent::render($request,$exception);
    }
}
