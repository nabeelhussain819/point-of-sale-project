<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class VendorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Vendor::insert(
        	[ 
				"name" 				=> "Demo Vendor",
				"telephone" 		=> "923001234567",
				"mailing_address" 	=> "Demo Address here",
				"website"		 	=> "https://abc.abc.com",
				"contact_title"		=> "Demo",
				"contact_number"	=> "923001236547",
				"contact_email"		=> "demo@demo.com",
				"description"		=> "Demo description goes here",
				"created_at" 		=> Carbon::now(0),
				"updated_at"		=> Carbon::now()
			]
        );
    }
}
