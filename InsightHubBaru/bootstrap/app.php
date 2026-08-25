<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../backend/routes/web.php',
        api: __DIR__.'/../backend/routes/api.php',
        commands: __DIR__.'/../backend/routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'force.change.password' => \App\Http\Middleware\ForceChangePassword::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create()
    ->useAppPath(dirname(__DIR__).'/backend/app')
    ->useDatabasePath(dirname(__DIR__).'/backend/database');

