<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Services\ProfileServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class StudentProfileController extends Controller
{
    protected ProfileServiceInterface $profileService;

    public function __construct(ProfileServiceInterface $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Get the authenticated user's profile.
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

        // Pastikan user adalah student
        if ($user->role !== 'student') {
            return response()->json([
                'message' => 'Access denied. Only students can access this resource.'
            ], 403);
        }

        try {
            $profile = $this->profileService->getStudentProfile($user);

            if (!$profile) {
                return response()->json([
                    'message' => 'Student profile not found.'
                ], 404);
            }

            // Format data sesuai dengan frontend
            $profileData = [
                'id' => $user->id,
                'name' => $profile->full_name,
                'email' => $user->email,
                'university' => $profile->university ?? '',
                'major' => $profile->major ?? '',
                'skills' => json_decode($profile->skills, true) ?? [],
                'location' => $profile->location ?? '',
                'interests' => json_decode($profile->interests, true) ?? [],
                'experience' => json_decode($profile->experience, true) ?? [],
                'education' => json_decode($profile->education, true) ?? [],
                'resume' => $profile->resume ?? '',
                'portfolio' => $profile->portfolio ?? '',
                'avatar' => $profile->avatar ?? '',
            ];

            return response()->json($profileData);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error retrieving profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the authenticated user's profile.
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

        // Pastikan user adalah student
        if ($user->role !== 'student') {
            return response()->json([
                'message' => 'Access denied. Only students can access this resource.'
            ], 403);
        }

        // Validasi input
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'university' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:100',
            'interests' => 'nullable|array',
            'interests.*' => 'string|max:100',
            'experience' => 'nullable|array',
            'experience.*' => 'string|max:500',
            'education' => 'nullable|array',
            'education.*' => 'string|max:500',
            'portfolio' => 'nullable|url|max:255',
            'avatar' => 'nullable|string', // Ini mungkin URL base64 encoded image
        ]);

        try {
            $profileData = $request->only([
                'name', 'email', 'university', 'major', 'location',
                'skills', 'interests', 'experience', 'education',
                'portfolio', 'avatar'
            ]);

            $profile = $this->profileService->updateStudentProfile($profileData, $user);

            // Jika email diubah, update di tabel users juga
            if ($request->has('email') && $request->email !== $user->email) {
                $user->update(['email' => $request->email]);
            }

            // Format data untuk response
            $responseData = [
                'id' => $user->id,
                'name' => $profile->full_name,
                'email' => $user->email,
                'university' => $profile->university ?? '',
                'major' => $profile->major ?? '',
                'skills' => json_decode($profile->skills, true) ?? [],
                'location' => $profile->location ?? '',
                'interests' => json_decode($profile->interests, true) ?? [],
                'experience' => json_decode($profile->experience, true) ?? [],
                'education' => json_decode($profile->education, true) ?? [],
                'resume' => $profile->resume ?? '',
                'portfolio' => $profile->portfolio ?? '',
                'avatar' => $profile->avatar ?? '',
            ];

            return response()->json([
                'message' => 'Profile updated successfully',
                'profile' => $responseData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating profile: ' . $e->getMessage()
            ], 500);
        }
    }
}