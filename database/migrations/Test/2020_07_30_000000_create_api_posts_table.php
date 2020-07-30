<?php

use DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_posts', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id');
            // $table->foreign('user_id')
            //       ->references('id')->on('users')
            //       ->onUpdate('cascade')
            //       ->onDelete('cascade');
            $table->unsignedInteger('post_id');

            $table->string('title', 190)->nullable();
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
        Schema::dropIfExists('api_posts');
    }
}
