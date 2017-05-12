<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsTable extends Migration
{
    public function up()
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('table_id')->unsigned()->nullable()->default(null);
            $table->string('type')->nullable()->default(null);
            $table->string('name')->nullable()->default(null);
            $table->string('title')->nullable()->default(null);
            $table->string('default')->nullable()->default(null);
        });
    }

    public function down()
    {
        Schema::dropIfExists('fields');
    }
}