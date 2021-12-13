<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Tools\CryptoBot\CCXTSkin;

class CreateAllCryptobotTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cryptobot_exchanges', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('exchange', 50)->unique();
            $table->boolean('has_fetch_tickers')->default(0);
            $table->boolean('has_fetch_ohlcv')->default(0);
            $table->boolean('is_active')->default(0);
            $table->text('data')->nullable();
            $table->string('url')->nullable();
            $table->string('url_api')->nullable();
            $table->string('url_doc')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        CCXTSkin::updateExchanges();

        Schema::create('cryptobot_pairs', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('exchange_id');
            $table->string('pair', 90)->nullable();
            $table->boolean('is_active')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['exchange_id', 'pair'], 'exchange_pair');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cryptobot_exchanges');

        Schema::drop('cryptobot_pairs');
    }
}
