<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('collection_id')->unsigned()->nullable()->default(null);
            $table->integer('position')->unsigned()->default(0);
            $table->string('title')->nullable()->default(null);
            $table->string('slug')->nullable()->default(null);
            $table->string('author')->nullable()->default(null);
            $table->date('date')->nullable()->default(null);
            $table->boolean('published')->nullable()->default(false);
            $table->text('preview')->nullable();
            $table->text('content')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
    