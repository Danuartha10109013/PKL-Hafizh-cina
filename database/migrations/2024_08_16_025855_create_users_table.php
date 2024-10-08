
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->varchar('username', 5);
            $table->char('name', 80);
            $table->char('email', 80)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->varchar('telephone', 15);
            $table->varchar('place_of_birth', 100);
            $table->date('date_of_birth');
            $table->varchar('gender', 50);
            $table->varchar('religion', 50);
            $table->text('address');
            $table->unsignedBigInteger('position')->nullable();
            $table->varchar('id_card', 16);
            $table->char('password', 80);
            $table->unsignedBigInteger('role')->nullable();
            $table->char('avatar', 80)->nullable();
            $table->enum('status', [0, 1]);
            $table->unsignedBigInteger('schedule')->nullable();
            $table->enum('delete', [0, 1])->default(0);
            $table->char('token', 80);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('role')->references('id')->on('roles');
            $table->foreign('schedule')->references('id')->on('schedules');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
