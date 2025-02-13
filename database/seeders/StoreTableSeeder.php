<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use App\Models\UserStore;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class StoreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $iStoreId =
        Store::insertGetId(
        	[
	        	"guid" 			=> Str::uuid(),
				"code"  		=> Store::code(),
        		'name' 			=> "Demo Store",
        		'full_name' 	=> "Demo Store",
				"location" 		=> "Pakistan",
				"city" 			=> "KHI",
				"state" 		=> "",
				"zip" 			=> "00000",
				"timezone"  	=> date('Y-m-d'),
				"contact_info"	=> "923001236547",
				"primary_phone"	=> "923001236547",
				"fax"			=>  "923001236547",
				"description"	=>  "This is demo store",
				"active" 		=> 1,
        		'created_at' 	=> Carbon::now(0),
        		'updated_at' 	=> Carbon::now()
        	]
        );

        $aUserIds = User::all()->pluck('id');
        $aRoleIds = Role::all()->pluck('id');

        $i = 0;
        $aData = array();
        foreach ($aUserIds as $iUserId=>$key) {
        	$aData[] = [
                'user_id' => $iUserId === 0 ? 1 : $iUserId,
        		'role_id' => $aRoleIds[$i],
        		'store_id' => $iStoreId,
        		'created_at' 	=> Carbon::now(0),
        		'updated_at' 	=> Carbon::now()
        	];
        	$i++;
        	# code...
        }
        UserStore::insert($aData);
    }
}


