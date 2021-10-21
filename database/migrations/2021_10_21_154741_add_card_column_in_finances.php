<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCardColumnInFinances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('finances_schedules', function (Blueprint $table) {
            $table->boolean("pay_by_card")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('finances_schedules', function (Blueprint $table) {
            $table->dropColumn("pay_by_card");
        });
    }
}
