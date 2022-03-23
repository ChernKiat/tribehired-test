<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllCryptobotTables5 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('cryptobot_currencies', function (Blueprint $table)
        // {
        //     $table->dropColumn(['deleted_at']);
        // });

        Schema::table('cryptobot_pair_strategy', function (Blueprint $table)
        {
            $table->boolean('is_base')->nullable()->default(null)->change();
        });

        // Schema::table('cryptobot_strategies', function (Blueprint $table)
        // {
        //     $table->boolean('is_same')->nullable()->after('is_active')->comment('3');
        // });


        Schema::table('cryptobot_exchanges', function (Blueprint $table)
        {
            $table->index(['deleted_at']);
        });

        Schema::table('cryptobot_pairs', function (Blueprint $table)
        {
            $table->index(['deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('cryptobot_currencies', function (Blueprint $table)
        // {
        //     $table->timestamp('deleted_at')->nullable()->after('cryptobot_strategy_id');
        // });

        Schema::table('cryptobot_pair_strategy', function (Blueprint $table)
        {
            $table->boolean('is_base')->default(1)->change();
        });

        // Schema::table('cryptobot_strategies', function (Blueprint $table)
        // {
        //     $table->dropColumn(['is_same']);
        // });


        Schema::table('cryptobot_exchanges', function (Blueprint $table)
        {
            $table->dropIndex(['deleted_at']);
        });

        Schema::table('cryptobot_pairs', function (Blueprint $table)
        {
            $table->dropIndex(['deleted_at']);
        });
    }
}
