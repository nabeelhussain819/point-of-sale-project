<?php

namespace Database\Seeders;

use App\Models\Category; 
use App\Models\Department; 
use App\Models\Product; 
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// $aUserIds = User::all()->pluck('id');
        $oDepartment = Department::take(1)->first();
        $oCategory = Category::take(1)->first();
        //
        Product::insert([
        	[
				"guid" 				=> Str::uuid(),
				"name" 				=> "Apple iPhoneX",
				"description" 		=> "Apple Mobile Phone",
				"UPC" 				=> "apple",
				"department_id" 	=> $oDepartment->id,
				"category_id" 		=> $oCategory->id,
				"cost" 				=> "20",
				"retail_price" 		=> "25",
				"has_serial_number" => "0",
				"min_price" 		=> "23",
				"taxable" 			=> 1,
				"active" 			=> 1,
				"created_at" 		=> Carbon::now(0),
				"updated_at"		=> Carbon::now()
			],[
				"guid" 				=> Str::uuid(),
				"name" 				=> "Motrolla PhoneX",
				"description" 		=> "Motrolla Mobile Phone",
				"UPC" 				=> "moto",
				"department_id" 	=> $oDepartment->id,
				"category_id" 		=> $oCategory->id,
				"cost" 				=> "12",
				"retail_price" 		=> "18",
				"has_serial_number" => "1",
				"min_price" 		=> "15",
				"taxable" 			=> 0,
				"active" 			=> 1,
				"created_at" 		=> Carbon::now(0),
				"updated_at"		=> Carbon::now()
			],[
				"guid" 				=> Str::uuid(),
				"name" 				=> "Huawei PhoneX",
				"description" 		=> "Huawei Mobile Phone",
				"UPC" 				=> "huawei",
				"department_id" 	=> $oDepartment->id,
				"category_id" 		=> $oCategory->id,
				"cost" 				=> "10",
				"retail_price" 		=> "15",
				"has_serial_number" => "0",
				"min_price" 		=> "13",
				"taxable" 			=> 0,
				"active" 			=> 1,
				"created_at" 		=> Carbon::now(0),
				"updated_at"		=> Carbon::now()
			]
        ]);
    }
}
