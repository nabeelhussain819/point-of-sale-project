<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepairs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repairs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('status')->default("INPROGRESS");
            $table->float('total_cost')->nullable();
            $table->float('advance_cost')->nullable();
            $table->uuid('guid');
            $table->timestamps();
        });

        Schema::create('repairs_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_type_id');
            $table->unsignedBigInteger('repair_id');

            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('issue_id');
            $table->longText('description')->nullable();
            $table->float('total_cost')->nullable();
            $table->string('device_unique_number')->nullable();
            $table->uuid('guid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repairs');
        Schema::dropIfExists('repairs_products');
    }
}
