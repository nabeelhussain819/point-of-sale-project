<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RepairPaymentScheduled extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repairs_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('repair_id');
            $table->float("received_amount")->nullable();
            $table->float("discount")->nullable();
            $table->float("additional_charge")->nullable()->comment("this is we added because on repair some time they need extra effort money");
            $table->boolean("pay_by_card")->default(false);
            $table->text("comment")->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnUpdate();
            $table->foreign('updated_by')->references('id')->on('users')->cascadeOnUpdate();
            $table->timestamps();
        });

        Schema::table('repairs_schedules', function (Blueprint $table) {
            $table->foreign('repair_id')
                ->references('id')
                ->on('repairs')
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
        Schema::dropIfExists('repairs_schedules');
    }
}
