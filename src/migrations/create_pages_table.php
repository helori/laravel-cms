<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('locale', 5)->nullable()->default('fr');
            $table->integer('parent_id')->unsigned()->default(0);
            $table->integer('position')->default(0);
            $table->boolean('published')->default(false);

            $table->string('menu_title')->nullable()->default(null);
            $table->string('menu_subtitle')->nullable()->default(null);

            $table->string('page_title')->nullable()->default(null);
            $table->string('page_subtitle')->nullable()->default(null);

            $table->string('alias')->nullable()->default(null);
            $table->string('type')->nullable()->default(null);

            $table->string('seo_title')->nullable()->default(null);
            $table->text('seo_description')->nullable();
            $table->text('seo_keywords')->nullable();

            $table->text('preview')->nullable();
            $table->text('content')->nullable();
            
            $table->string('author')->nullable()->default(null);
            $table->text('tags')->nullable();

            $table->string('video')->nullable()->default(null);
            $table->string('url')->nullable()->default(null);
        });
    }

    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
