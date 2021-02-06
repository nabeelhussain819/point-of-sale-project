<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    // Reset cached roles and permissions
    app()['cache']->forget('spatie.permission.cache');

    Role::create(['name' => 'super-admin']);
    $admin = User::create([
        'name' => 'Super Admin',
        'email' => 'superadmin@admin.com',
        'password' => Hash::make('admin')
    ]);
    $admin->assignRole('super-admin');
    
    Role::create(['name' => 'sales']);
    $sales = User::create([
        'name' => 'john Doe',
        'email' => 'johndoe@sales.com',
        'password' => Hash::make('password')
    ]);
    $sales->assignRole('sales');

    Role::create(['name' => 'user']);
    $user = User::create([
        'name' => 'Mark',
        'email' => 'mark@gmail.com',
        'password' => Hash::make('password')
    ]);
    $user->assignRole('user');

    }
}
