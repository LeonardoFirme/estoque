<?php
// bootstrap/app.php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Redirecionamentos padrão
        $middleware->redirectGuestsTo(fn() => route('login'));
        $middleware->redirectUsersTo(fn() => route('dashboard'));

        // REGISTRO DO MIDDLEWARE: Criando o alias para usar nas rotas
        $middleware->alias([
            'prevent-back' => \App\Http\Middleware\PreventBackHistory::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();