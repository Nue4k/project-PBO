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
        // Hapus tabel lama jika ada (dari migrasi default Laravel)
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');

        // Buat tabel users dengan UUID
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Ganti $table->id()
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['student', 'company', 'admin']); // Kita gunakan enum() Laravel, ini bekerja di MySQL
            // Kita hapus 'name', 'email_verified_at', 'remember_token' untuk saat ini,
            // karena data profil akan di tabel terpisah. Tambahkan kembali jika diperlukan.
            $table->timestamps();
        });

        // Buat tabel password_reset_tokens yang sesuai
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Buat tabel sessions yang sesuai
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->uuid('user_id')->nullable()->index(); // Gunakan uuid()
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     * Karena ini menggantikan migrasi default, 'down' mungkin tidak bisa sepenuhnya kembali ke skema default Laravel.
     * Kita drop tabel yang kita buat.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
