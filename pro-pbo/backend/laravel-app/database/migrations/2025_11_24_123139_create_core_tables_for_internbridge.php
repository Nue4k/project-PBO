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
        // Tabel Profil Mahasiswa
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->unique(); // Harus unique karena satu user hanya punya satu profil student
            $table->string('full_name');
            $table->string('university')->nullable();
            $table->string('major')->nullable();
            $table->decimal('gpa', 3, 2)->nullable(); // IPK
            $table->integer('graduation_year')->nullable();
            $table->enum('status', ['undergraduate', 'fresh_graduate'])->default('undergraduate');
            $table->text('bio')->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('linkedin_url', 255)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Tabel Profil Perusahaan
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->unique(); // Harus unique karena satu user hanya punya satu profil company
            $table->string('company_name');
            $table->text('description')->nullable();
            $table->string('industry', 100)->nullable();
            $table->string('website_url', 255)->nullable();
            $table->text('address')->nullable();
            $table->string('logo_url', 255)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Tabel Dokumen
        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->string('title', 100); // misal: "CV 2024", "Transkrip"
            $table->string('file_url', 255); // Path/URL file di storage
            $table->string('file_type', 10); // pdf, docx, dll
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('student_profiles')->onDelete('cascade');
        });

        // Tabel Lowongan (Jobs)
        Schema::create('jobs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->string('title', 150);
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->enum('job_type', ['wfo', 'wfh', 'hybrid']);
            $table->string('location', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('closing_date')->nullable(); // Batas akhir pendaftaran
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('company_profiles')->onDelete('cascade');
        });

        // Tabel Lamaran (Applications)
        Schema::create('applications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('job_id');
            $table->uuid('student_id');
            $table->uuid('resume_id')->nullable(); // CV spesifik yang dipakai melamar
            $table->enum('status', ['applied', 'reviewing', 'interview', 'accepted', 'rejected'])->default('applied');
            $table->text('feedback_note')->nullable(); // Catatan dari perusahaan untuk mahasiswa
            $table->timestamps();

            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('student_profiles')->onDelete('cascade');
            $table->foreign('resume_id')->references('id')->on('documents')->onDelete('set null'); // Jika CV dihapus, jangan hapus lamaran
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('company_profiles');
        Schema::dropIfExists('student_profiles');
    }
};
