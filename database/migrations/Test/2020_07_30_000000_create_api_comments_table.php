<?php

use DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_comments', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('post_id');
            // $table->foreign('post_id')
            //       ->references('id')->on('posts')
            //       ->onUpdate('cascade')
            //       ->onDelete('cascade');
            $table->unsignedInteger('comment_id');

            $table->string('name', 190)->nullable();
            $table->string('email', 190)->nullable();
            $table->text('body');
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
        Schema::dropIfExists('api_comments');
    }
}
