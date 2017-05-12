<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablesTable extends Migration
{
    public function up()
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('alias')->nullable()->default(null);
            $table->string('table')->nullable()->default(null);
            $table->string('title')->nullable()->default(null);

            $table->boolean('in_admin')->default(true);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tables');
    }
}
