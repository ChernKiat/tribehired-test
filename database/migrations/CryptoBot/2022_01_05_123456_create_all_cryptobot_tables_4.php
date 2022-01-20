<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllCryptobotTables4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cryptobot_currencies', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name', 45);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('cryptobot_pairs', function (Blueprint $table)
        {
            $table->unsignedInteger('cryptobot_quote_currency_id')->nullable()->after('cryptobot_exchange_id'); // ->index();
            $table->unsignedInteger('cryptobot_base_currency_id')->nullable()->after('cryptobot_quote_currency_id'); // ->index();
        });

        Schema::table('cryptobot_pair_strategy', function (Blueprint $table)
        {
            $table->boolean('is_base')->default(1)->after('cryptobot_strategy_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cryptobot_currencies');

        Schema::table('cryptobot_pairs', function (Blueprint $table) {
            $table->dropColumn(['cryptobot_base_currency_id', 'cryptobot_quote_currency_id']);
        });

        Schema::table('cryptobot_pair_strategy', function (Blueprint $table) {
            $table->dropColumn(['type']);
        });
    }
}
