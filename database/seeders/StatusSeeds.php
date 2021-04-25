<?php

namespace Database\Seeders;

use App\Models\Finance;
use App\Status;
use Illuminate\Database\Seeder;

class StatusSeeds extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('statuses')->insertOrIgnore([
            ['id' => 1, 'name' => 'Pending', 'alias' => 'PENDING', 'color' => '#f7dd19', 'system' => true, 'model' => Finance::class],
            ['id' => 2, 'name' => 'Payment Due', 'alias' => 'PAYMENT_DUE', 'color' => '#ffbcb8', 'system' => true, 'model' => Finance::class],
            ['id' => 3, 'name' => 'Completed', 'alias' => 'COMPLETED', 'color' => '#b8fc65', 'system' => true, 'model' => Finance::class],
            ['id' => 4, 'name' => 'Cancelled/ Void', 'alias' => 'CANCELLED', 'color' => '#ffbcb8', 'system' => true, 'model' => Finance::class],
        ]);
    }
}
