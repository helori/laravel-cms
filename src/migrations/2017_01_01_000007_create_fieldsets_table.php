<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsetsTable extends Migration
{
    public function up()
    {
        Schema::create('fieldsets', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('title')->nullable()->default(null);
        });
    }

    public function down()
    {
        Schema::dropIfExists('fieldsets');
    }
}
    