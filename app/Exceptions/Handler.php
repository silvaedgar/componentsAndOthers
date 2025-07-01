<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;


class Handler extends ExceptionHandler
{
    protected $dontReport = [];
    //

    protected $dontFlash = ['password', 'password_confirmation'];

    public function report(Throwable $exception) {
        parent::report($exception);
    }




    public function render($request, Throwable $exception) {
        info("Exception en Hanlder {$exception->getCode()} {$exception->getMessage()}", [$exception]);
        // Si
        // Si es una excepción ModelNotFoundException, devolvemos un JSON
        if ($exception instanceof ModelNotFoundException) {
            return new JsonResponse([
                'message' => 'Registro no encontrado.',
                'error' => 'No se pudo encontrar el recurso solicitado.'
            ], Response::HTTP_NOT_FOUND);
        }

        // Capturamos otras excepciones para evitar `null` en la respuesta
        $defaultResponse = new JsonResponse([
            'message' => 'Error interno en el servidor.',
            'error' => $exception->getMessage(),
        ], Response::HTTP_INTERNAL_SERVER_ERROR);

        return parent::render($request, $exception) ?? $defaultResponse;
    }
}
