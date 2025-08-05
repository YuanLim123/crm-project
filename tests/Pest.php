<?php

use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\Client;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}

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

function createProject()
{
    $client = createClient();

    $project =  Project::factory()
        ->has(Task::factory()->count(3))
        ->create([
            'client_id' => $client->id,
        ]);
    return $project;
}


function createClient()
{
    return Client::factory()->create();
}
