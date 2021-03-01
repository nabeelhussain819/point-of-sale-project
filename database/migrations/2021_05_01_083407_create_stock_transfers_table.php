<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('request');
            $table->unsignedBigInteger('store_in');
            $table->unsignedBigInteger('store_out');
            $table->string('reference');
            $table->bigInteger('quantity');
            $table->unsignedBigInteger('inventory_id')->nullable();
            $table->date('date');
            $table->timestamps();
        });
        Schema::table('stock_transfers', function (Blueprint $table){
            $table->foreign('store_in')->references('id')->on('stores')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('store_out')->references('id')->on('stores')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('inventory_id')->references('id')->on('inventories')->cascadeOnUpdate()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_transfers');
    }
}
