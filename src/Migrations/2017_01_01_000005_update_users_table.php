<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table)
        {
            $table->string('firstname')->nullable()->default(null);
            $table->string('lastname')->nullable()->default(null);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table)
        {
            $table->dropColumn([
                'firstname',
                'lastname',
            ]);
        });
    }
};
