<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionsTable extends Migration
{
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('title')->nullable()->default(null);
            $table->string('slug')->nullable()->default(null);
        });
    }

    public function down()
    {
        Schema::dropIfExists('collections');
    }
}
    