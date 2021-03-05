<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PurchaseOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('price');
            $table->float('expected_price')->nullable();
            $table->float('shiping_cost')->nullable();
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('store_id');
            $table->dateTime("expected_date");
            $table->dateTime("received_date")->nullable();
            $table->timestamps();
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->foreign('store_id')->references('id')->on('stores')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('vendor_id')->references('id')->on('vendors')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_orders');
    }
}
