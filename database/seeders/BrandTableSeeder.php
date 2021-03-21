<?php

namespace Database\Seeders;

use App\Models\Brand; 
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	//
        Brand::insert(
        	[
	        	[
	        		'name' 			=> "Apple",
	        		'guid' 			=> Str::uuid(), 
	        		'created_at' 	=> Carbon::now(0),
	        		'updated_at' 	=> Carbon::now()
	        	],[
	        		'name' 			=> "Huawei", 
	        		'guid' 			=> Str::uuid(),
	        		'created_at' 	=> Carbon::now(0),
	        		'updated_at' 	=> Carbon::now()
	        	],[
	        		'name' 			=> "Samsung", 
	        		'guid' 			=> Str::uuid(),
	        		'created_at' 	=> Carbon::now(0),
	        		'updated_at' 	=> Carbon::now()
	        	]
        	]
        );
    }
}
