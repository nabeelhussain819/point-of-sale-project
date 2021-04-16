<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReconciliationProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reconciliation_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reconciliation_id');
            $table->unsignedBigInteger('product_id');
            $table->float('physical_quantity')->nullable();
            $table->float('system_quantity')->nullable();
            $table->float('adjust_quantity')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnUpdate();
            $table->foreign('updated_by')->references('id')->on('users')->cascadeOnUpdate();
        });

        Schema::table('reconciliation_products', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('reconciliation_id')->references('id')->on('reconciliations')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reconciliation_products');
    }
}
