<?php

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

beforeAll(function () {
    Artisan::call('migrate');
    Artisan::call('db:seed', [
        '--class' => RolesSeeder::class,
    ]);
});

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

function setupAdminUser()
{
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
    $user = User::factory()->create();
    $user->assignRole('admin');

    return $user;
}


