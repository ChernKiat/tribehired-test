<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Skins\CryptoBot\CCXTSkin;

class CreateAllRoobetTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roobethack_dictionaries', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('hexadecimal_number')->index();
            $table->string('my')->index();
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
        Schema::drop('roobethack_dictionaries');
    }
}
