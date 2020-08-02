<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('netjunkies_posts', function (Blueprint $table) {
        // Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('source')->nullable();
            $table->text('url');

            $table->timestamp('crawled_at')->nullable();
            $table->tinyInteger('crawled_status')->nullable();
            $table->text('title')->nullable();
            $table->string('image')->nullable();
            $table->timestamp('posted_at')->nullable();
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
        Schema::dropIfExists('netjunkies_posts');
        // Schema::dropIfExists('posts');
    }
}
