<?php

namespace Tests\Feature\Settings;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * WM-04: DJ Punzalan · https://github.com/EE2506 · {{AUTHOR_EMAIL}}
 */
class SessionRevocationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_revoke_the_current_browser_session(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->delete(route('sessions.revoke-current'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
        $this->assertDatabaseMissing('sessions', ['user_id' => $user->id]);
        $response->assertSessionHas('inertia.clear_history', true);

        $loginResponse = $this->get(route('login'));
        $loginResponse->assertOk();
        $this->assertStringContainsString('"clearHistory":true', $loginResponse->getContent());
    }

    public function test_session_revocation_requires_recent_password_confirmation(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('sessions.revoke-current'));

        $response->assertRedirect(route('password.confirm'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_revoke_all_sessions_without_affecting_other_users(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        DB::table('sessions')->insert([
            [
                'id' => 'other-device-session',
                'user_id' => $user->id,
                'ip_address' => '192.0.2.1',
                'user_agent' => 'Test browser',
                'payload' => '',
                'last_activity' => time(),
            ],
            [
                'id' => 'unrelated-user-session',
                'user_id' => $otherUser->id,
                'ip_address' => '192.0.2.2',
                'user_agent' => 'Other browser',
                'payload' => '',
                'last_activity' => time(),
            ],
        ]);

        $response = $this->actingAs($user)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->delete(route('sessions.revoke-all'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
        $response->assertSessionHas('inertia.clear_history', true);
        $this->assertDatabaseMissing('sessions', ['user_id' => $user->id]);
        $this->assertDatabaseHas('sessions', [
            'id' => 'unrelated-user-session',
            'user_id' => $otherUser->id,
        ]);
    }

    public function test_authenticated_pages_are_not_cached_and_encrypt_inertia_history(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('dashboard'));

        $response->assertOk()
            ->assertHeader('Cache-Control', 'no-store, private')
            ->assertHeader('Pragma', 'no-cache')
            ->assertHeader('Expires', '0');
        $this->assertStringContainsString('"encryptHistory":true', $response->getContent());
    }
}
