<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('team_invites', function (Blueprint $table) {
            $table->enum('invite_type', ['membership', 'ownership'])->default('membership');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('team_invites', function (Blueprint $table) {
            $table->dropColumn('invite_type');
        });
    }
};
