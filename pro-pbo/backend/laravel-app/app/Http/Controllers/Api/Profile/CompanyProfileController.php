<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Services\ProfileServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CompanyProfileController extends Controller
{
    protected ProfileServiceInterface $profileService;

    public function __construct(ProfileServiceInterface $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Get the authenticated company user's profile.
     *
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        $user = Auth::user();

        // Pastikan user terotentikasi
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        // Pastikan user adalah company
        if ($user->role !== 'company') {
            return response()->json([
                'message' => 'Access denied. Only companies can access this resource.'
            ], 403);
        }

        try {
            $profile = $this->profileService->getCompanyProfile($user);

            if (!$profile) {
                return response()->json([
                    'message' => 'Company profile not found.'
                ], 404);
            }

            // Format data sesuai dengan frontend - account for field name differences
            $profileData = [
                'id' => $user->id,
                'name' => $profile->company_name,
                'email' => $user->email,
                'description' => $profile->description ?? '',
                'industry' => $profile->industry ?? '',
                'location' => $profile->address ?? '',  // Map database address to frontend location
                'contactEmail' => $profile->contact_email ?? $user->email,  // Map database contact_email to frontend contactEmail
                'contactPhone' => $profile->contact_phone ?? '',  // Map database contact_phone to frontend contactPhone
                'website' => $profile->website_url ?? '',  // Map database website_url to frontend website
                'logo' => $profile->logo_url ?? '',
            ];

            return response()->json($profileData);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error retrieving profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the authenticated company user's profile.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $user = Auth::user();

        // Pastikan user terotentikasi
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        // Pastikan user adalah company
        if ($user->role !== 'company') {
            return response()->json([
                'message' => 'Access denied. Only companies can access this resource.'
            ], 403);
        }

        // Validasi input
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'description' => 'nullable|string',
            'industry' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'contactEmail' => 'nullable|email',
            'contactPhone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|string', // Ini mungkin URL base64 encoded image
        ]);

        try {
            $profileData = $request->only([
                'name', 'email', 'description', 'industry', 'location',
                'contactEmail', 'contactPhone', 'website', 'logo'
            ]);

            $profile = $this->profileService->updateCompanyProfile($profileData, $user);

            // Jika email diubah, update di tabel users juga
            if ($request->has('email') && $request->email !== $user->email) {
                $user->update(['email' => $request->email]);
            }

            // Format data untuk response - account for field name differences
            $responseData = [
                'id' => $user->id,
                'name' => $profile->company_name,
                'email' => $user->email,
                'description' => $profile->description ?? '',
                'industry' => $profile->industry ?? '',
                'location' => $profile->address ?? '',  // Map database address to frontend location
                'contactEmail' => $profile->contact_email ?? $user->email,  // Map database contact_email to frontend contactEmail
                'contactPhone' => $profile->contact_phone ?? '',  // Map database contact_phone to frontend contactPhone
                'website' => $profile->website_url ?? '',  // Map database website_url to frontend website
                'logo' => $profile->logo_url ?? '',
            ];

            return response()->json([
                'message' => 'Company profile updated successfully',
                'profile' => $responseData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating profile: ' . $e->getMessage()
            ], 500);
        }
    }
}