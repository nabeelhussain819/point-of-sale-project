<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('product_id');
            $table->tinyInteger('type')->comment('Layaway or InStore');
            $table->string('status')->default('PENDING');
            $table->float('total');
            $table->float('advance');
            $table->float('payable')->comment('remaining amount should updated')->nullable();
            $table->float('duration_period')->comment('e.g 3 months 3 should be in there')->nullable();
            $table->string('duration_period_unit')->default('Month');
            $table->float('duration_due_date')->nullable()->comment('due date of duration')->nullable();
            $table->longText('comments')->nullable();
            $table->longText('serial_number')->nullable()->comment('');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
//            Customer Detail
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('customer_card_number')->nullable()->comment('only last 4 digits');
            $table->date('customer_card_expiry')->nullable();
            $table->string('customer_card_ccv')->nullable()->comment('this is not used just for further client verification');

            //            Customer Detail
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnUpdate();
            $table->foreign('updated_by')->references('id')->on('users')->cascadeOnUpdate();
            $table->timestamps();
        });

        Schema::table('finances', function (Blueprint $table) {
            $table->foreign('customer_id')
                ->references('id')->on('customers')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('store_id')
                ->references('id')->on('stores')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('product_id')
                ->references('id')->on('products')
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
        Schema::dropIfExists('finances');
    }
}
