<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('unauthenticated users are redirected to login', function () {
    $response = $this->get('/');

    $response->assertRedirect('/login');
});

test('authenticated user can view dashboard', function () {
    $user = User::factory()->create([
        'role' => 'admin',
    ]);

    $response = $this->actingAs($user)->get('/');

    $response->assertStatus(200);
});
