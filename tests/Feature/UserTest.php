<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;


uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = createUser();
});

test('authenticated user can access the user page', function () {
    $this->actingAs($this->user); // pretend to be logged in as the user

    $response = $this->get('/users');

    $response->assertStatus(200);
});


test('unauthenticated user cannot access the user page', function () {
    $response = $this->get('/users');

    $response->assertRedirect('/login');

    $response->assertStatus(302);
});


test('user page contains created user data', function () {
    $this->actingAs($this->user);
    
    $reponse = $this->get('/users');

    $reponse ->assertInertia(
            fn(Assert $page) =>
            $page->component('User/Index')
                ->has('users.data', 1,)
                ->has(
                    'users.data.0',
                    fn(Assert $page) => $page
                        ->where('id', $this->user->id)
                        ->where('name', $this->user->name)
                        ->etc()
                )
        );
});

describe('users', function () {
    beforeEach(function () {
        $this->users = createUsers(9);
    });

    test('paginated users table doesnt contain 11th record', function () {
        $this->actingAs($this->users->first());

        $response = $this->get('/users');

        $response->assertInertia(
                fn(Assert $page) =>
                $page->component('User/Index')
                    ->has('users.data', 10)
            );
    });
});


test('admin can delete a user', function () {

    $userToDelete = createUser();
    $admin = createAdminUser();
    $this->actingAs($admin);

    $response = $this->delete('/users/' . $userToDelete->id);

    $response->assertStatus(302);
    $response->assertRedirect('/users');

    $this->assertSoftDeleted($userToDelete);
});


test('user cannot delete another user', function () {
    $this->actingAs($this->user);
    $userToDelete = createUser();

    $response = $this->delete('/users/' . $userToDelete->id);

    $response->assertStatus(403);
});


test('admin can update a user', function () {
    $admin = createAdminUser();
    $this->actingAs($admin);
    $updatedUser = [
        'name' => 'Updated User',
        'email' => 'updateduser@example.com',
    ];

    $response = $this->put(route('users.update', $this->user->id), $updatedUser);

    $response->assertStatus(302);
    $response->assertRedirect('/users');
    $this->assertDatabaseHas('users', [
        'id' => $this->user->id,
        'name' => $updatedUser['name'],
        'email' => $updatedUser['email'],
    ]);
});


test('user cannot update a user', function () {
    $this->actingAs($this->user);
    $updatedUser = [
        'name' => 'Updated User',
        'email' => 'updateduser@example.com',
    ];

    $response = $this->put(route('users.update', $this->user->id), $updatedUser);

    $response->assertStatus(403);
    $response->assertForbidden();
});


test('create user successful', function () {
    $this->actingAs($this->user);
    createAdminUser();

    $user = [
        'name' => 'New User',
        'email' => 'newuser@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    $response = $this->post('/users', $user);

    $response->assertStatus(302);
    $response->assertRedirect('/users');

    $this->assertDatabaseHas('users', [
        'name' => $user['name'],
        'email' => $user['email'],
    ]);

    $result = User::where('email', $user['email'])->first();
    $this->assertSame($user['name'], $result->name);
});


