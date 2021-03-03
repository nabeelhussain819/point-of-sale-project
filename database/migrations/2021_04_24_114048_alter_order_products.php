<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('order_products', function (Blueprint $table){
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('stock_bin_id')->default(1);
            $table->unsignedBigInteger('type_id');
            $table->foreign('stock_bin_id')->references('id')->on('stock_bins')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('store_id')->references('id')->on('stores')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('type_id')->references('id')->on('types')->cascadeOnDelete()->cascadeOnUpdate();
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
