<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogArticlesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('blog_articles')) {
            Schema::create('blog_articles', function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();

                $table->timestamp('published_at')->nullable()->default(null);
                $table->boolean('published')->default(false);
                
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
        Schema::dropIfExists('blog_articles');
    }
}
