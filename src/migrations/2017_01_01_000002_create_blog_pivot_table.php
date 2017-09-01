<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogPivotTable extends Migration
{
    public function up()
    {
        Schema::create('blog_article_category', function (Blueprint $table) {
            $table->integer('blog_article_id')->unsigned()->nullable();
            $table->integer('blog_category_id')->unsigned()->nullable();
            
            $table->foreign('blog_article_id')->references('id')->on('blog_articles')->onDelete('cascade');
            $table->foreign('blog_category_id')->references('id')->on('blog_categories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_article_category');
    }
}
    