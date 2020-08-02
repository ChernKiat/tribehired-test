<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('netjunkies_reactions', function (Blueprint $table) {
        // Schema::create('reactions', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('comment_id');
            $table->foreign('comment_id')
                  ->references('id')->on('comments')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->tinyInteger('type');
            $table->unsignedInteger('total')->default(0);
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
        Schema::dropIfExists('netjunkies_reactions');
        // Schema::dropIfExists('reactions');
    }
}
