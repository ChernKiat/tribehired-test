<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllNftstorageTables2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nftstorage_multiverses', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('keyword')->unique();
            $table->text('description')->nullable();
            $table->smallInteger('transaction_fee')->default(0);
            $table->string('wallet_address')->nullable();
            $table->timestamps();
        });

        Schema::create('nftstorage_assets', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('nftstorage_multiverse_id')->index();
            $table->unsignedInteger('index')->index();
            $table->string('hex');
            $table->unsignedInteger('unit')->default(1);
            $table->unsignedInteger('states_total')->default(1);
            $table->string('random')->nullable();
            $table->string('extension', 8);
            $table->text('sha256');
            $table->tinyInteger('type')->default(1);
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
        Schema::drop('nftstorage_multiverses');

        Schema::drop('nftstorage_assets');
    }
}
