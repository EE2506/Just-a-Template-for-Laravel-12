<?php

use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
        $exceptions->respond(function (
            SymfonyResponse $response,
            Throwable $exception,
            Request $request,
        ): SymfonyResponse {
            $status = $response->getStatusCode();

            if (
                $status < 400
                || $status >= 600
                || $request->is('api/*')
                || $request->expectsJson()
            ) {
                return $response;
            }

            $retryAfter = $response->headers->get('Retry-After');
            $label = SymfonyResponse::$statusTexts[$status]
                ?? ($status < 500 ? 'Client Error' : 'Server Error');

            $errorPage = Inertia::render('errors/HttpError', [
                'status' => $status,
                'label' => $label,
                'retryAfter' => is_numeric($retryAfter) ? (int) $retryAfter : null,
            ])->toResponse($request);

            $errorPage->setStatusCode($status);
            $errorPage->headers->add($response->headers->all());
            $errorPage->headers->set('Cache-Control', 'no-store, private');

            return $errorPage;
        });
    })->create();
