<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail; // Comment or remove if not using email verification
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne; // Import for relations

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    // Specify UUID key type and disable auto-incrementing
    public $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id', // Include id for UUID
        'email',
        'password',
        'role', // Add role
        // Add other fields if necessary (e.g., name if still needed for some auth purposes)
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        // 'remember_token', // Comment out if 'remember_token' column is also removed from the DB, otherwise keep it
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        // Remove 'email_verified_at' cast if the column is removed
        return [
            'password' => 'hashed',
            // 'email_verified_at' => 'datetime', // Remove if not using
        ];
    }

    // --- Relasi ---
    // Satu User bisa memiliki satu StudentProfile
    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class, 'user_id', 'id');
    }

    // Satu User bisa memiliki satu CompanyProfile
    public function companyProfile(): HasOne
    {
        return $this->hasOne(CompanyProfile::class, 'user_id', 'id');
    }
}
