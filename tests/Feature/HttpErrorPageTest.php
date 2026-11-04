<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Inertia\Testing\AssertableInertia as Assert;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

/**
 * WM-04: DJ Punzalan · https://github.com/EE2506 · {{AUTHOR_EMAIL}}
 */
class HttpErrorPageTest extends TestCase
{
    public function test_browser_not_found_uses_the_status_aware_error_page(): void
    {
        $response = $this->get('/this-page-does-not-exist');

        $response->assertNotFound()
            ->assertInertia(fn (Assert $page) => $page
                ->component('errors/HttpError')
                ->where('status', 404)
                ->where('label', 'Not Found')
            );
    }

    public function test_browser_rate_limit_error_shows_retry_time_and_keeps_headers(): void
    {
        Route::middleware('web')->get('/test-errors/rate-limited', function () {
            abort(429, 'Too many requests', ['Retry-After' => '42']);
        });

        $response = $this->get('/test-errors/rate-limited');

        $response->assertStatus(429)
            ->assertHeader('Retry-After', '42')
            ->assertHeader('Cache-Control', 'no-store, private')
            ->assertInertia(fn (Assert $page) => $page
                ->component('errors/HttpError')
                ->where('status', 429)
                ->where('retryAfter', 42)
            );
    }

    public function test_browser_server_error_uses_a_generic_message_without_exception_details(): void
    {
        Route::middleware('web')->get('/test-errors/server-error', function () {
            throw new \RuntimeException('Sensitive internal exception detail');
        });

        $response = $this->get('/test-errors/server-error');

        $response->assertStatus(500)
            ->assertInertia(fn (Assert $page) => $page
                ->component('errors/HttpError')
                ->where('status', 500)
                ->where('label', 'Internal Server Error')
            );

        $this->assertStringNotContainsString('Sensitive internal exception detail', $response->getContent());
    }

    public function test_json_errors_keep_the_framework_json_response(): void
    {
        Route::middleware('web')->get('/test-errors/json', function () {
            abort(429, 'Too many requests', ['Retry-After' => '42']);
        });

        $response = $this->getJson('/test-errors/json');

        $response->assertStatus(429)
            ->assertHeader('Retry-After', '42')
            ->assertJsonPath('message', 'Too many requests');
    }

    public function test_browser_error_page_covers_every_client_and_server_error_status(): void
    {
        Route::middleware('web')->get('/test-errors/{status}', function (int $status) {
            abort($status);
        });

        foreach (range(400, 599) as $status) {
            $this->get("/test-errors/{$status}")
                ->assertStatus($status)
                ->assertInertia(fn (Assert $page) => $page
                    ->component('errors/HttpError')
                    ->where('status', $status)
                    ->where('label', Response::$statusTexts[$status] ?? ($status < 500 ? 'Client Error' : 'Server Error'))
                );
        }
    }
}
