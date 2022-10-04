<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supportsystem_users', function (Blueprint $table) {
        // Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_name')->nullable();
            $table->string('user_email')->nullable();
            $table->string('user_image')->nullable();
            $table->tinyInteger('user_gender')->nullable();
            $table->integer('birthday_year')->nullable();
            $table->text('to')->nullable();
            $table->tinyInteger('message')->nullable();
            $table->unsignedInteger('voucher_code')->nullable();
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
        Schema::dropIfExists('supportsystem_users');
        // Schema::dropIfExists('users');
    }
}
