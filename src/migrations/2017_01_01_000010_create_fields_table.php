<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('fields')) {
            Schema::create('fields', function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();

                $table->integer('fieldset_id')->unsigned()->nullable()->default(null);
                $table->foreign('fieldset_id')->references('id')->on('fieldsets')->onDelete('cascade');

                $table->string('type')->nullable()->default('text');
                $table->string('name')->nullable()->default(null);
                $table->string('title')->nullable()->default(null);
                $table->string('default')->nullable()->default(null);

                $table->boolean('use_when_create')->default(true);
                $table->boolean('use_when_update')->default(true);
                $table->boolean('show_in_list')->default(true);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('fields');
    }
}
