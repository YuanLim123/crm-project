<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        Permission::create(['name' => 'edit_user']);
        Permission::create(['name' => 'delete_user']);

        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(['edit_user', 'delete_user']);

        Role::create(['name' => 'user']);
    }
}
