<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use LogicException;

/**
 * WM-04: DJ Punzalan · https://github.com/EE2506 · {{AUTHOR_EMAIL}}
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (! app()->isLocal()) {
            throw new LogicException('Demo accounts may only be seeded in the local environment.');
        }

        $demoUsers = [
            ['name' => 'Test User', 'email' => 'user@123.test', 'role' => UserRole::User],
            ['name' => 'Test Admin', 'email' => 'admin@123.test', 'role' => UserRole::Admin],
            ['name' => 'Test Superadmin', 'email' => 'superadmin@123.test', 'role' => UserRole::Superadmin],
            ['name' => 'Test Developer', 'email' => 'dev@123.test', 'role' => UserRole::Developer],
        ];

        foreach ($demoUsers as $attributes) {
            User::query()
                ->firstOrNew(['email' => $attributes['email']])
                ->forceFill([
                    ...$attributes,
                    'password' => '123456789',
                    'email_verified_at' => now(),
                ])
                ->save();
        }
    }
}
