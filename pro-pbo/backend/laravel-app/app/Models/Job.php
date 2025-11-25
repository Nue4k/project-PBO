<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory;

    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'company_id',
        'title',
        'description',
        'requirements',
        'job_type', // wfo, wfh, hybrid
        'location',
        'is_active',
        'closing_date',
    ];

    // --- Relasi ---
    // Satu Job dimiliki oleh satu CompanyProfile
    public function companyProfile(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id', 'id');
    }

    // Satu Job bisa memiliki banyak Application
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'job_id', 'id');
    }
}