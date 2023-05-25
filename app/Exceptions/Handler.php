<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if (!$request->expectsJson()) {
            return parent::render($request, $e);
        }
        switch (true) {
            case $e instanceof ModelNotFoundException:
                return response()->json([
                    'message' => 'Record not found',
                ], 404);
                break;
            case $e instanceof NotFoundHttpException:
                return response()->json([
                    'message' => 'Page not found',
                ], 404);
                break;
            case $e instanceof AuthenticationException:
                return response()->json([
                    'message' => 'Unauthenticated',
                ], 401);
                break;
            case $e instanceof ValidationException:
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 400);
                break;
            default:
                return response()->json([
                    'message' => 'Internal Server Error',
                ], 500);
                break;
        }
    }
}
