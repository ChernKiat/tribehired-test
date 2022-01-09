<?php

namespace App\Traits\CryptoBot;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

trait DynamicDatabaseTrait
{
    protected $table = '';

    public static function setTableName($table_name)
    {
        self::existTable($table_name);

        return self::from($table_name);
    }

    public static function existTableName($table_name)
    {
        if (!Schema::hasTable($table_name)) {
            Schema::create($table_name, function(Blueprint $table)
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
    }
}
