<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentProfile extends Model
{
    use HasFactory;

    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'full_name',
        'university',
        'major',
        'gpa',
        'graduation_year',
        'status', // undergraduate, fresh_graduate
        'bio',
        'phone_number',
        'linkedin_url',
        'skills',
        'interests',
        'experience',
        'education',
        'portfolio',
        'avatar',
        'location',
        'resume',
    ];

    // --- Relasi ---
    // Satu StudentProfile dimiliki oleh satu User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Satu StudentProfile bisa memiliki banyak Document
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'student_id', 'id');
    }

    // Satu StudentProfile bisa memiliki banyak Application
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'student_id', 'id');
    }
}