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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enhancer');
            $table->date('date');
            $table->enum('status', [0, 1]);
            $table->string('reason', 255);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('leaves', function (Blueprint $table) {
            $table->foreign('enhancer')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
