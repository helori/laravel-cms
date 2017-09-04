<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('menus')) {
            Schema::create('menus', function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();

                $table->integer('position')->unsigned()->default(0);
                $table->string('title')->nullable()->default(null);
                $table->string('slug')->nullable()->default(null);
                $table->string('subtitle')->nullable()->default(null);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
