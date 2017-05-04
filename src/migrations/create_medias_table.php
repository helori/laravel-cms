<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediasTable extends Migration
{
    public function up()
    {
        Schema::create('medias', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            
            //$table->morphs('mediable');
            $table->string('mediable_id')->default(0);
            $table->string('mediable_type')->nullable()->default(null);

            $table->integer('position')->unsigned()->default(0);
            $table->string('collection')->nullable()->default(null);
            $table->string('filepath')->nullable()->default(null);
            $table->string('filename')->nullable()->default(null);
            $table->string('title')->nullable()->default(null);
            $table->string('type')->nullable()->default(null);
            $table->string('mime')->nullable()->default(null);
            $table->string('extension')->nullable()->default(null);
            $table->string('size')->default(0);
            $table->string('width')->default(0);
            $table->string('height')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('medias');
    }
}
