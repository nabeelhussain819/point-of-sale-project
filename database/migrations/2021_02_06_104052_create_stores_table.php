<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('guid');
            $table->string('name');
            $table->string('full_name');
            $table->string('code')->unique();
            $table->string('location');
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->date('timezone');
            $table->string('contact_info');
            $table->string('primary_phone');
            $table->string('fax');
            $table->string('description')->nullable();
            $table->boolean('active')->default(false);
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
        Schema::dropIfExists('stores');
    }
}
