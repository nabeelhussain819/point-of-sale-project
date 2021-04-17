<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('parent_id')->references('id')->on('orders')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
