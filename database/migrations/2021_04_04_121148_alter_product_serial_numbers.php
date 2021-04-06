<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductSerialNumbers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_serial_numbers', function (Blueprint $table) {
            $table->unsignedBigInteger('purchase_order_id')->nullable();
            $table->unsignedBigInteger('stock_transfer_id')->nullable();
            $table->foreign('stock_transfer_id')->references('id')->on('stock_transfers')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->cascadeOnUpdate()->cascadeOnDelete();
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
