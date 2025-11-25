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
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->json('skills')->nullable(); // Array of skills
            $table->json('interests')->nullable(); // Array of interests
            $table->json('experience')->nullable(); // Array of experiences
            $table->json('education')->nullable(); // Array of education history
            $table->string('portfolio')->nullable(); // URL to portfolio
            $table->text('avatar')->nullable(); // URL or base64 string for avatar
            $table->text('location')->nullable(); // Location field
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'skills',
                'interests',
                'experience',
                'education',
                'portfolio',
                'avatar',
                'location'
            ]);
        });
    }
};
