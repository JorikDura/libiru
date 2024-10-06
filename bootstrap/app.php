<?php

use App\Http\Middleware\LanguageMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: [
            __DIR__.'/../routes/api/v1/users.php',
            __DIR__.'/../routes/api/v1/auth.php',
            __DIR__.'/../routes/api/v1/people.php',
            __DIR__.'/../routes/api/v1/publishers.php',
            __DIR__.'/../routes/api/v1/books.php',
            __DIR__.'/../routes/api/v1/genres.php',
            __DIR__.'/../routes/api/v1/tags.php',
            __DIR__.'/../routes/api/v1/posts.php'
        ],
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up'
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append([
            LanguageMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
