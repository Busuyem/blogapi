<?php

use Illuminate\Foundation\Application;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'jwt.auth' => \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
            'jwt.refresh' => \Tymon\JWTAuth\Http\Middleware\RefreshToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (UnauthorizedHttpException $e) {
            $previous = $e->getPrevious();

            if ($previous instanceof TokenExpiredException) {
                return response()->json(['message' => 'Token has expired'], 401);
            } elseif ($previous instanceof TokenInvalidException) {
                return response()->json(['message' => 'Token is invalid'], 401);
            } elseif ($previous instanceof JWTException) {
                return response()->json(['message' => 'Token not provided'], 401);
            }

            return response()->json(['message' => 'Unauthorized'], 401);
        });
    })->create();
