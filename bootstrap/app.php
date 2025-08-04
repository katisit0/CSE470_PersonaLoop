<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        //check on every request if the user has selected a persona today
        $middleware->alias(['check.persona' => \App\Http\Middleware\EnsurePersonaSelected::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
