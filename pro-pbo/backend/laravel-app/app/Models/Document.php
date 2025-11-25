<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'student_id',
        'title',
        'file_url',
        'file_type',
    ];

    // --- Relasi ---
    // Satu Document dimiliki oleh satu StudentProfile
    public function studentProfile(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_id', 'id');
    }

    // Satu Document bisa digunakan dalam banyak Application (resume_id)
    // public function applications(): HasMany
    // {
    //     return $this->hasMany(Application::class, 'resume_id', 'id');
    //     // Perlu diingat: resume_id di Application bisa null
    // }
}