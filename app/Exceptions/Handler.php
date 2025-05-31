<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException; // Importa esta clase
use Illuminate\Http\JsonResponse; // Importa esta clase

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // Si la solicitud espera JSON (es una API), devuelve JSON 401
        if ($request->expectsJson()) {
            return new JsonResponse(['message' => $exception->getMessage()], 401);
        }

        // Si no espera JSON (es una web tradicional), redirige a la ruta 'login'
        return redirect()->guest($exception->redirectTo() ?? route('login'));
    }
}
