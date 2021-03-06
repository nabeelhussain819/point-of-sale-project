<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TransaferOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('request_id')->nullable();
            $table->dateTime('transfer_date')->nullable();
            $table->dateTime('received_date')->nullable();
            $table->unsignedBigInteger('transfer_by')->nullable();
            $table->unsignedBigInteger('received_by')->nullable();
            $table->unsignedBigInteger('store_in_id')->nullable();
            $table->unsignedBigInteger('store_out_id')->nullable();
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();
        });

        Schema::table('transfer_orders', function (Blueprint $table) {

            $table->foreign('store_in_id')->references('id')
                ->on('stores')->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreign('store_out_id')->references('id')
                ->on('stores')->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreign('transfer_by')->references('id')
                ->on('users')->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreign('received_by')->references('id')
                ->on('users')->cascadeOnUpdate()->cascadeOnDelete();

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
