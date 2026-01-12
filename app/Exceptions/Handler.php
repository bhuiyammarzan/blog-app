<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        // Handle invalid / missing Sanctum token
        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired token',
            ], 401);
        }

        return parent::render($request, $exception);
    }
}
