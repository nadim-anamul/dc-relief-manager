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
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'permission' => \App\Http\Middleware\PermissionMiddleware::class,
        ]);

        // Apply locale after session is started (web group)
        $middleware->appendToGroup('web', \App\Http\Middleware\SetLocale::class);
        
        // Convert Bengali numbers to English numbers for form processing
        $middleware->appendToGroup('web', \App\Http\Middleware\ConvertBengaliNumbers::class);
        
        // Update current economic year (after locale is set)
        $middleware->appendToGroup('web', \App\Http\Middleware\UpdateCurrentEconomicYearMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
