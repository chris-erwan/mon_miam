<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // On indique ici le fichier racine minimal pour l'accueil
        web: __DIR__.'/../routes/web.php', 
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        // ➜ On dit ici à Laravel de charger AUSSI vos routes de modules !
        then: function () {
            Route::middleware('web')
                ->group(base_path('app/Modules/Auth/routes/web.php'));
                
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('app/Modules/Auth/routes/api.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
