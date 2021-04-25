<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(BrandTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(StockTableSeeder::class);
        $this->call(DepartmentTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(StoreTableSeeder::class);
        $this->call(ProductTableSeeder::class);
        $this->call(VendorTableSeeder::class);
        $this->call(StatusSeeds::class);
    }
}
