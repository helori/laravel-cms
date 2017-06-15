<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionsPagesRelationship extends Migration
{
    public function up()
    {
        Schema::create('collection_page', function (Blueprint $table) {
            $table->integer('collection_id')->unsigned();
            $table->integer('page_id')->unsigned();
            
            $table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('collection_page');
    }
}
    