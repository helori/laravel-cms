<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->rememberToken();
            $table->string('email')->unique()->nullable()->default(null);
            $table->string('password', 60)->nullable()->default(null);
            $table->string('name')->nullable()->default(null);
            $table->boolean('activated')->default(1);
        });
    }

    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
