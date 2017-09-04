<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediablesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('mediables')) {
            Schema::create('mediables', function (Blueprint $table) {
                $table->integer('media_id')->unsigned()->nullable();
                
                $table->integer('mediable_id')->unsigned()->nullable();
                $table->string('mediable_type')->nullable()->default(null);

                $table->foreign('media_id')->references('id')->on('medias')->onDelete('cascade');

                $table->integer('position')->unsigned()->default(0);
                $table->string('collection')->nullable()->default(null);
                $table->string('table')->nullable()->default(null);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('mediables');
    }
}
    