<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefund extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->float('return_cost');
            $table->timestamps();
        });

        Schema::create('refunds_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('refund_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->string('serial_no')->nullable();
            $table->float('return_cost');
            $table->float('quantity')->nullable();
            $table->timestamps();
        });

        Schema::table('refunds', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnUpdate();
        });

        Schema::table('refunds_products', function (Blueprint $table) {
            $table->foreign('refund_id')->references('id')->on('refunds')->cascadeOnUpdate();
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnUpdate();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refund');
    }
}
