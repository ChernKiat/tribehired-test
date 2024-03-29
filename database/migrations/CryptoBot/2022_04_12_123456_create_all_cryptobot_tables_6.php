<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllCryptobotTables6 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cryptobot_logs', function(Blueprint $table)
        {
            $table->increments('id');
            $table->tinyInteger('type');
            $table->text('log');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        Schema::table('cryptobot_pairs', function (Blueprint $table)
        {
            $table->text('reported_on')->nullable()->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cryptobot_logs');

        Schema::table('cryptobot_pairs', function (Blueprint $table)
        {
            $table->dropColumn(['reported_on']);
        });
    }
}
