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
    Schema::table('show_attendance', function (Blueprint $table) {
        $table->time('start')->nullable(); // Menyimpan jam mulai
        $table->time('end')->nullable();   // Menyimpan jam selesai
    });
}

/**
 * Reverse the migrations.
 */
public function down(): void
{
    Schema::table('show_attendance', function (Blueprint $table) {
        $table->dropColumn(['start', 'end']);
    });
}

};
