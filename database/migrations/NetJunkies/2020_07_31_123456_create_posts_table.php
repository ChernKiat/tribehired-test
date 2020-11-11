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

            $table->string('user_name')->nullable();
            $table->string('user_image')->nullable();
            $table->tinyInteger('main_type')->default(1)->comment('1 = text, 2 = image');
            $table->text('title')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('reaction_total')->default(0);
            $table->timestamp('posted_at')->nullable();

            $table->timestamp('crawled_at')->nullable();
            $table->tinyInteger('crawler_status')->nullable();

            $table->tinyInteger('live_status')->default(0);
            $table->string('video')->nullable();
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
