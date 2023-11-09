<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('spreadsheet_data', function (Blueprint $table) {
            $table->increments('id');
            $table->string('clmn_a', 20);
            $table->string('clmn_b', 20);
        });
    }

    public function down()
    {
        Schema::drop('spreadsheet_data');
    }
};
