<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediasTable extends Migration
{
    public function up()
    {
        Schema::create('medias', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('filepath')->nullable()->default(null);
            $table->string('filename')->nullable()->default(null);
            $table->string('title')->nullable()->default(null);
            $table->string('type')->nullable()->default(null);
            $table->string('mime')->nullable()->default(null);
            $table->string('extension')->nullable()->default(null);
            $table->string('size')->default(0);
            $table->string('width')->default(0);
            $table->string('height')->default(0);
            $table->string('decache')->nullable()->default(null);
            $table->string('copyright')->nullable()->default(null);
        });
    }

    public function down()
    {
        Schema::dropIfExists('medias');
    }
}
