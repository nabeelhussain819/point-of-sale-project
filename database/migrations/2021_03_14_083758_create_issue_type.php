<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssueType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issue_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->uuid('guid');
            $table->timestamps();
        });

        Schema::table('repairs', function (Blueprint $table) {
            $table->foreign('customer_id')
                ->references('id')->on('customers')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('store_id')
                ->references('id')->on('stores')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });

        Schema::table('repairs_products', function (Blueprint $table) {
            $table->foreign('device_type_id')->references('id')->on('devices_types')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('brand_id')->references('id')->on('brands')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('repair_id')->references('id')->on('repairs')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('issue_id')->references('id')->on('issue_types')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });


        Schema::table('devices_types_brands_products', function (Blueprint $table) {
            $table->foreign('device_type_id')->references('id')->on('devices_types')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('brand_id')->references('id')->on('brands')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')
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
        Schema::dropIfExists('issue_types');
    }
}
