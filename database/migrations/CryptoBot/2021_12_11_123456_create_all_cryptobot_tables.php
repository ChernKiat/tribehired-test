<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Skins\CryptoBot\CCXTSkin;

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
            $table->unsignedInteger('cryptobot_exchange_id');
            $table->string('pair', 90)->nullable();
            $table->boolean('is_active')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['cryptobot_exchange_id', 'pair'], 'exchange_pair');
        });

        CCXTSkin::updatePairs();

        Schema::create('cryptobot_tickers', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('cryptobot_exchange_id'); // ->index();
            $table->unsignedInteger('cryptobot_pair_id'); // ->index();
            $table->bigInteger('timestamp'); // ->index();
            $table->timestamp('datetime')->nullable();
            $table->decimal('high', 15, 6)->nullable();
            $table->decimal('low', 15, 6)->nullable();
            $table->decimal('bid', 15, 6)->nullable();
            $table->decimal('bid_volume', 15, 6)->nullable();
            $table->decimal('ask', 15, 6)->nullable();
            $table->decimal('ask_volume', 15, 6)->nullable();
            $table->decimal('vwap', 15, 6)->nullable(); // volume-weighted average price
            $table->decimal('open', 15, 6)->nullable();
            $table->decimal('close', 15, 6)->nullable();
            // $table->decimal('first', 15, 6)->nullable();
            $table->decimal('last', 15, 6)->nullable();
            $table->decimal('change', 15, 6)->nullable();
            $table->decimal('percentage', 15, 6)->nullable();
            $table->decimal('average', 15, 6)->nullable();
            $table->decimal('base_volume', 15, 6)->nullable();
            $table->decimal('quote_volume', 15, 6)->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['cryptobot_exchange_id', 'cryptobot_pair_id', 'timestamp'], 'exchange_pair_timestamp');
        });

        Schema::create('cryptobot_ohlcvs', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('cryptobot_exchange_id'); // ->index();
            $table->unsignedInteger('cryptobot_pair_id'); // ->index();
            $table->bigInteger('timestamp'); // ->index();
            $table->timestamp('datetime')->nullable();
            $table->decimal('open', 15, 6)->nullable();
            $table->decimal('high', 15, 6)->nullable();
            $table->decimal('low', 15, 6)->nullable();
            $table->decimal('close', 15, 6)->nullable();
            $table->decimal('volume', 15, 6)->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['cryptobot_exchange_id', 'cryptobot_pair_id', 'timestamp'], 'exchange_pair_timestamp');
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

        Schema::drop('cryptobot_tickers');

        Schema::drop('cryptobot_ohlcvs');
    }
}
