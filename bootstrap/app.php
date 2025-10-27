<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, Request $request) {

            if ($e  instanceof MethodNotAllowedHttpException) {
                return response()->json([
                    'message' => 'Método no permitido para esta ruta.',
                    'error' => $e->getMessage()
                ], 405); // <- Esto es 405
            }

            info("Exception en App {$e->getCode()} {$e->getMessage()}", [$e]);

            // 🔹 Si es ModelNotFoundException, devolver JSON antes de que Laravel lo convierta en NotFoundHttpException
            if ($e instanceof Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                info("ES: {$e->getCode()} {$e->getMessage()}");
                return view('exceptions.message');

                // return response()->json([
                //     'message' => 'Registro no encontrado.',
                //     'error' => 'No se pudo encontrar el recurso solicitado.'
                // ], 404);
            }


            // 🔹 Para otras excepciones, usa la vista de error
            $message = $e->getMessage();
            return view('exceptions.message1', compact('message'));
        });
    })->create();
