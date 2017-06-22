<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTags extends Migration
{
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('title')->nullable()->default(null);
            $table->string('slug')->nullable()->default(null);
        });

        Schema::create('taggables', function (Blueprint $table) {
            $table->integer('tag_id')->unsigned()->nullable();
            
            $table->integer('taggable_id')->unsigned()->nullable();
            $table->string('taggable_type')->nullable()->default(null);

            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('taggables');
        Schema::dropIfExists('tags');
    }
}
    