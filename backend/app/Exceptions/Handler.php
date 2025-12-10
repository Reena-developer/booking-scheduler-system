<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException && $request->expectsJson()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $exception->errors(),
                'code' => 'VALIDATION_ERROR'
            ], 422);
        }

        return parent::render($request, $exception);
    }
}