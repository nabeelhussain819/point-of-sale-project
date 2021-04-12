<?php

namespace Database\Seeders;

use App\Models\StockBin;
use App\Models\Type;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class StockTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $stockItems = [
            'Retail',
            'Return'
        ];

        $types = [
            'Simple-Order',
            'Purchase-Order',
            'Purchase-Receive'
        ];

        foreach ($stockItems as $items)
        {
            StockBin::create(['name' => $items, 'Constant' => $items, 'system' => true]);
        }

        foreach ($types as $items)
        {
            Type::insert(['name' => $items, 'created_at' => Carbon::now(0), 'updated_at' => Carbon::now()]);
        }
    }
}
