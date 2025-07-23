<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create a default admin user
        User::factory()->create([
            'name' => 'Zhi Yuan',
            'email' => 'zhiyuan.lim@asj.com.sg',
            'password' => Hash::make('Admin123.'),
        ]);

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('Admin123.'),
        ])->syncRoles('admin'); // we need to override the default user role

        User::factory()
            ->count(20)
            ->create();
    }
}
