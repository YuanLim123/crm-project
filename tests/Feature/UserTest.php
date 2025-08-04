<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = createUser();
});

test('authenticated user can access the user page', function () {
    $this->actingAs($this->user); // pretend to be logged in as the user

    $response = $this->get('/users');
    $response->assertStatus(200);
})->skip();


test('unauthenticated user cannot access the user page', function () {
    $response = $this->get('/users');
    $response->assertRedirect('/login');
    $response->assertStatus(302);
})->skip();


test('user page contains created user data', function () {
    $this->actingAs($this->user);
    $this->get('/users')
        ->assertInertia(
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
})->skip();

describe('users', function () {
    beforeEach(function () {
        $this->users = createUsers(9);
    });

    test('paginated users table doesnt contain 11th record', function () {
        $this->actingAs($this->users->first());

        $this->get('/users')
            ->assertInertia(
                fn(Assert $page) =>
                $page->component('User/Index')
                    ->has('users.data', 10)
            );
    });
})->skip();


test('admin can delete a user', function () {

    $admin = createAdminUser();
    $this->actingAs($admin);

    $this->delete('/users/' . $this->user->id)
        ->assertStatus(302)
        ->assertRedirect('/users');
});


test('user cannot delete another user', function () {
    $this->actingAs($this->user);

    $userToDelete = createUser();

    $response = $this->delete('/users/' . $userToDelete->id);
    $response->assertStatus(403); // Forbidden
});


function createUser(): User
{
    Role::firstOrCreate(['name' => 'user']);
    return User::factory()->create();
}


function createUsers($count = 1)
{
    Role::firstOrCreate(['name' => 'user']);
    return User::factory($count)->create();
}

function createAdminUser(): User
{
    Permission::firstOrCreate(['name' => 'edit_user']);
    Permission::firstOrCreate(['name' => 'delete_user']);

    $adminRole = Role::firstOrCreate(['name' => 'admin']);
    $adminRole->givePermissionTo(['edit_user', 'delete_user']);

    $admin = User::factory()->create([
        'name' => 'admin',
        'email' => 'admin@admin.com',
        'password' => Hash::make('Admin123.'),
    ]);

    $admin->syncRoles('admin'); // we need to override the default user role

    return $admin;
}