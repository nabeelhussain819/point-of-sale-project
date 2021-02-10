<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDetail;
use Carbon\Carbon;
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
    UserDetail::create([
        'user_id' => $admin->id,
        'first_name' => 'super-admin',
        'last_name' => 'super-admin',
        'DOB' => Carbon::now(),
        'phone' => '+923156986335',
    ]);

    $admin->assignRole('super-admin');
    
    Role::create(['name' => 'sales']);
    $sales = User::create([
        'name' => 'john Doe',
        'email' => 'johndoe@sales.com',
        'password' => Hash::make('password')
    ]);
    UserDetail::create([
        'user_id' => $sales->id,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'DOB' => Carbon::now(),
        'phone' => '+932241432',
    ]);

    $sales->assignRole('sales');

    Role::create(['name' => 'user']);
    $user = User::create([
        'name' => 'Mark',
        'email' => 'mark@gmail.com',
        'password' => Hash::make('password')
    ]);
    UserDetail::create([
        'user_id' => $user->id,
        'first_name' => 'Mark',
        'last_name' => 'Johnson',
        'DOB' => Carbon::now(),
        'phone' => '+932241432',
    ]);
    $user->assignRole('user');

    }
}
