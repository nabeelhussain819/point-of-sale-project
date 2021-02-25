<?php

namespace Database\Seeders;

use App\Models\Stock;
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
            'Return',
            'Repair'
        ];

        foreach ($stockItems as $items)
        {
            Stock::create(['name' => $items, 'Constant' => $items]);
        }
    }
}
