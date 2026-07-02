<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['web', 'admin'])->group(__DIR__ . '/../routes/admin.php');
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => App\Http\Middleware\AdminMiddleware::class,
            'master_admin' => App\Http\Middleware\CheckMasterAdmin::class,
            'permission' => App\Http\Middleware\CheckPermission::class,
        ]);
        $middleware->redirectUsersTo(function (Request $request) {
            return route('admin.dashboard');
        });
        $middleware->redirectGuestsTo(function (Request $request) {
            return route('admin.login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
