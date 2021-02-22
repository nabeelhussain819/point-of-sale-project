<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('quantity');
            $table->string('order_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('inventory_id');
            $table->timestamps();
        });

        Schema::table('order_products', function (Blueprint $table){
           $table->foreign('inventory_id')->references('id')->on('inventories')->cascadeOnUpdate()->cascadeOnDelete();
           $table->foreign('customer_id')->references('id')->on('customers')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_products');
    }
}
