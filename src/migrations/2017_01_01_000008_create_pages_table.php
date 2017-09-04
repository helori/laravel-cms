<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('pages')) {
            Schema::create('pages', function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();

                $table->boolean('published')->default(false);
                $table->integer('position')->unsigned()->default(0);
                $table->integer('menu_id')->unsigned()->nullable()->default(null);
                
                $table->string('title')->nullable()->default(null);
                $table->string('slug')->nullable()->default(null);
                $table->text('preview')->nullable();
                $table->text('content')->nullable();

                $table->string('seo_title')->nullable()->default(null);
                $table->string('seo_description')->nullable()->default(null);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
