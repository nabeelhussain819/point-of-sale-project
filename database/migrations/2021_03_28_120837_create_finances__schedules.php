<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancesSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finances_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('finance_id');
            $table->date("date_of_payment");
            $table->date("due_date");
            $table->float("amount");
            $table->float("received_amount")->nullable();
            $table->date("received_date")->nullable();
            $table->string("status");
            $table->timestamps();
        });

        Schema::table('finances_schedules', function (Blueprint $table) {
            $table->foreign('finance_id')
                ->references('id')
                ->on('finances')
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
        Schema::dropIfExists('finances__schedules');
    }
}
