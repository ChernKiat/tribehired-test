<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllCryptobotTables3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cryptobot_strategies', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->tinyInteger('type')->default(1);
            $table->tinyInteger('state')->default(1);
            $table->text('comment')->nullable();
            $table->boolean('is_active')->default(0);
            $table->unsignedInteger('cryptobot_currency_id')->nullable()->comment('2');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('cryptobot_pair_strategy', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('cryptobot_pair_id'); // ->index();
            $table->unsignedInteger('cryptobot_strategy_id'); // ->index();
            $table->timestamps();
        });

        Schema::table('cryptobot_orders', function (Blueprint $table)
        {
            $table->unsignedInteger('cryptobot_strategy_id')->nullable()->after('id'); // ->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cryptobot_strategies');

        Schema::drop('cryptobot_pair_strategy');

        Schema::table('cryptobot_orders', function (Blueprint $table)
        {
            $table->dropColumn(['cryptobot_strategy_id']);
        });
    }
}
