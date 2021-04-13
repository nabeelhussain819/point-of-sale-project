<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->uuid('guid');
            $table->bigInteger('quantity');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->timestamps();
        });

        Schema::table('inventories', function (Blueprint $table){
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnUpdate();
            $table->foreign('vendor_id')->references('id')->on('vendors')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventories');
    }
}
