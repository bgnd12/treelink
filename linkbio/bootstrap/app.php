<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'active' => \App\Http\Middleware\EnsureAccountIsActive::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

if (env('VERCEL')) {
    $runtimeStoragePath = sys_get_temp_dir().'/treelink';
    $app->useStoragePath($runtimeStoragePath);

    foreach ([
        'app/private',
        'framework/cache/data',
        'framework/sessions',
        'framework/views',
        'logs',
    ] as $directory) {
        $path = $runtimeStoragePath.'/'.$directory;

        if (! is_dir($path) && ! mkdir($path, 0775, true) && ! is_dir($path)) {
            throw new RuntimeException('Unable to create Vercel runtime directory: '.$path);
        }
    }
}

return $app;

