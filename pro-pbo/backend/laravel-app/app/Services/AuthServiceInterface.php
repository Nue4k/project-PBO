<?php

namespace App\Services;

interface AuthServiceInterface
{
    /**
     * Register a new user.
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function register(array $data): \App\Models\User;

    /**
     * Authenticate a user and return a token (or user object).
     *
     * @param string $email
     * @param string $password
     * @return array // e.g., ['user' => ..., 'token' => ...]
     */
    public function login(string $email, string $password): array;

    /**
     * Logout a user (revoke token if using token-based auth).
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function logout(\App\Models\User $user): bool;
}