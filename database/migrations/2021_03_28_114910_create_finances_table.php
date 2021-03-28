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
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('store_id');
            $table->tinyInteger('type')->comment("Layaway or InStore");
            $table->string("status")->default("PENDING");
            $table->float('total');
            $table->float('advance');
            $table->float('payable')->comment("remaining amount should updated");
            $table->float('duration_period')->comment("e.g 3 months 3 should be in there");
            $table->string('duration_period_unit')->default('Month');
            $table->float('duration_due_date')->nullable()->comment("due date of duration");
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->float('installment');
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
