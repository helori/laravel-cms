<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('parent_id')->unsigned()->nullable()->default(null);
            $table->integer('position')->unsigned()->default(0);
            $table->string('menu')->nullable()->default(null);
            $table->string('title')->nullable()->default(null);
            $table->text('subtitle')->nullable();
            $table->string('slug')->nullable()->default(null);
            $table->date('date')->nullable()->default(null);
            $table->boolean('published')->nullable()->default(false);
            $table->text('content')->nullable();
            $table->text('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('seo_keywords')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
    