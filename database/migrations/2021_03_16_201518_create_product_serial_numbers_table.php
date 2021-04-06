<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSerialNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_serial_numbers', function (Blueprint $table) {
            $table->id()->autoIncrement()->unsigned();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('store_id');
            $table->text('serial_no')->unique();
            $table->longText('imei_no')->nullable();
            $table->tinyInteger('is_sold')->default("0")->comment("0 => unsold, 1 => sold");
            $table->timestamps();
        });

        Schema::table('product_serial_numbers', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('store_id')->references('id')->on('stores')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_serial_numbers');
    }
}
