<?php

namespace Database\Seeders;

use App\Models\Department; 
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Department::insert(
        	[
        		'name' 			=> "Demo Department",
        		'full_name' 	=> "Demo Department",
        		'reference' 	=> "DemoDepartment",
				"active" 		=> 1,
        		'created_at' 	=> Carbon::now(0),
        		'updated_at' 	=> Carbon::now()
        	]
        );
    }
}
