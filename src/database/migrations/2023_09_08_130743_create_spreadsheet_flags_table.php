<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('spreadsheet_flags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_name')->unique();
            $table->boolean('imported');
            $table->integer('rows_imported');
            $table->integer('total_rows');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('spreadsheet_flags');
    }
};
