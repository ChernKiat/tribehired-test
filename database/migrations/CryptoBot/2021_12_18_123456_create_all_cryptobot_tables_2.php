<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllCryptobotTables2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cryptobot_orders', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('cryptobot_exchange_id'); // ->index();
            $table->unsignedInteger('cryptobot_pair_id'); // ->index();
            $table->string('exchange_trade_id', 90)->nullable();
            $table->string('exchange_order_id', 90)->nullable();
            $table->bigInteger('timestamp')->nullable(); // ->index();
            $table->timestamp('datetime')->nullable();
            $table->enum('type', ['market', 'limit'])->nullable();
            $table->enum('side', ['buy', 'sell'])->nullable();
            $table->decimal('price', 15, 6)->nullable();
            $table->decimal('amount', 15, 6)->nullable();
            $table->decimal('fee_cost', 15, 6)->nullable();
            $table->decimal('fee_rate', 15, 6)->nullable();
            $table->string('fee_currency', 90)->nullable();
            $table->boolean('is_own')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['cryptobot_exchange_id', 'cryptobot_pair_id', 'exchange_trade_id', 'exchange_order_id'], 'exchange_pair_trade_order');
        });

        Schema::create('cryptobot_trades', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('cryptobot_exchange_id'); // ->index();
            $table->unsignedInteger('cryptobot_pair_id'); // ->index();
            $table->string('exchange_trade_id', 90)->nullable();
            $table->string('exchange_order_id', 90)->nullable();
            $table->bigInteger('timestamp')->nullable(); // ->index();
            $table->timestamp('datetime')->nullable();
            $table->enum('type', ['market', 'limit'])->nullable();
            $table->enum('side', ['buy', 'sell'])->nullable();
            $table->decimal('price', 15, 6)->nullable();
            $table->decimal('amount', 15, 6)->nullable();
            $table->decimal('fee_cost', 15, 6)->nullable();
            $table->decimal('fee_rate', 15, 6)->nullable();
            $table->string('fee_currency', 90)->nullable();
            $table->boolean('is_own')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['cryptobot_exchange_id', 'cryptobot_pair_id', 'exchange_trade_id', 'exchange_order_id'], 'exchange_pair_trade_order');
        });

        Schema::table('cryptobot_exchanges', function (Blueprint $table)
        {
            $table->dropColumn(['has_fetch_tickers', 'has_fetch_ohlcv']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cryptobot_orders');

        Schema::drop('cryptobot_trades');

        Schema::table('cryptobot_exchanges', function (Blueprint $table)
        {
            $table->boolean('has_fetch_tickers')->default(0)->after('exchange');
            $table->boolean('has_fetch_ohlcv')->default(0)->after('has_fetch_tickers');
        });
    }
}
