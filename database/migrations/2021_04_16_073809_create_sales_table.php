<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->bigInteger('quantity')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->timestamps();
        });

        Schema::table('sales', function (Blueprint $table){
            $table->foreign('product_id')->references('id')->on('inventories')->cascadeOnDelete()->cascadeOnUpdate();
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
