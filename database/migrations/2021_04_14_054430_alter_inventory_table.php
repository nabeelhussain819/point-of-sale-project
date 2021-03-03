<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('inventories', function (Blueprint $table){
            $table->bigInteger('lookup');
            $table->string('description');
            $table->string('UPC')->nullable();
            $table->float('cost',36);
            $table->float('extended_cost',36);
            $table->string('adjustment')->nullable();
            $table->unsignedBigInteger('stock_bin_id');
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->cascadeOnDelete()->cascadeOnUpdate();
        });
        Schema::table('inventories', function (Blueprint $table) {
            $table->foreign('stock_bin_id')->references('id')->on('stock_bins')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
