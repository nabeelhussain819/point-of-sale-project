<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->float('discount')->nullable();
            $table->float('without_tax')->nullable();
            $table->float('sub_total')->nullable();
            $table->float('tax')->nullable()->comment("save the % of tax at that time ");
            $table->float('without_discount')->nullable();
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table){

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
