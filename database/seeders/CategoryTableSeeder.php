<?php

namespace Database\Seeders;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Category::insert(
        	[
        		'name' 			=> "MOBILE",
        		'full_name' 	=> "Demo Category",
        		'reference' 	=> "DemoCategory",
				"active" 		=> 1,
        		'created_at' 	=> Carbon::now(0),
        		'updated_at' 	=> Carbon::now()
        	]
        );
    }
}
