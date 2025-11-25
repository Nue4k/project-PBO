<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthService implements AuthServiceInterface
{
    /**
     * Register a new user.
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function register(array $data): User
    {
        $validator = Validator::make($data, [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:student,company,admin',
            // Validasi tambahan untuk data profil bisa ditambahkan di service profil terpisah
            // Atau, validasi bisa dilakukan sebelum memanggil service ini.
        ]);

        if ($validator->fails()) {
            // Jika validasi gagal, Laravel biasanya mengembalikan RedirectResponse dengan error.
            // Di service, kita bisa melempar ValidationException.
            $validator->validate(); // Ini akan melempar ValidationException jika gagal
        }

        $validatedData = $validator->validated();

        $user = User::create([
            'id' => \Illuminate\Support\Str::uuid(), // Generate UUID
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']), // Hash password
            'role' => $validatedData['role'],
        ]);

        // Berdasarkan role, buat profil terkait (Student atau Company)
        // Ini bisa dipindahkan ke service profil terpisah jika diinginkan.
        // Misalnya: $this->profileService->createProfileForUser($user, $data);
        // Untuk sekarang, kita buat profil kosong secara otomatis.
        if ($user->role === 'student') {
            $user->studentProfile()->create([
                'id' => \Illuminate\Support\Str::uuid(),
                'full_name' => $data['full_name'] ?? $user->email, // Gunakan email sebagai fallback
                // Tambahkan field lain dari $data jika diperlukan
            ]);
        } elseif ($user->role === 'company') {
            $user->companyProfile()->create([
                'id' => \Illuminate\Support\Str::uuid(),
                'company_name' => $data['company_name'] ?? $user->email,
                // Tambahkan field lain dari $data jika diperlukan
            ]);
        }

        return $user;
    }

    /**
     * Authenticate a user and return a token.
     *
     * @param string $email
     * @param string $password
     * @return array
     */
    public function login(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Hapus token lama (opsional, tergantung kebijakan)
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Logout a user (revoke current token).
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function logout(\App\Models\User $user): bool
    {
        // Revoke the token that was used to authenticate the current request...
        $user->currentAccessToken()->delete();
        return true;
    }
}