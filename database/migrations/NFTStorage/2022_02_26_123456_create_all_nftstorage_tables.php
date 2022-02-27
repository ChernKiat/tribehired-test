<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Skins\CryptoBot\CCXTSkin;

class CreateAllNftstorageTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nftstorage_manekis', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('index')->index();
            $table->text('sha256');
            $table->tinyInteger('type')->nullable()->after('updated_user_id');
            $table->unsignedInteger('unit')->default(1);
            $table->string('maneki', 6)->unique();
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
        Schema::drop('nftstorage_manekis');
    }
}
