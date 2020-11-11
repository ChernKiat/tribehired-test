<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('netjunkies_comments', function (Blueprint $table) {
        // Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('post_id');
            $table->foreign('post_id')
                  ->references('id')->on('netjunkies_posts')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->string('user_name')->nullable();
            $table->string('user_image')->nullable();
            $table->text('comment');
            $table->unsignedInteger('reaction_total')->default(0);
            $table->timestamp('posted_at')->nullable();

            $table->boolean('is_selected')->default(0);
            $table->integer('live_order')->nullable();
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
        Schema::dropIfExists('netjunkies_comments');
        // Schema::dropIfExists('comments');
    }
}
