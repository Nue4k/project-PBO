<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class StudentProfileController extends Controller
{
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

        // Ambil profile student terkait
        $profile = $user->studentProfile;

        // Jika profile belum ada, buat profil kosong
        if (!$profile) {
            $profile = $user->studentProfile()->create([
                'id' => \Illuminate\Support\Str::uuid(),
                'full_name' => $user->email, // Gunakan email sebagai default nama
                'university' => '',
                'major' => '',
                'location' => '',
                'skills' => json_encode([]),
                'interests' => json_encode([]),
                'experience' => json_encode([]),
                'education' => json_encode([]),
                'portfolio' => '',
                'avatar' => '',
                'resume' => '',
            ]);
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

        // Ambil atau buat profile student
        $profile = $user->studentProfile;
        if (!$profile) {
            $profile = $user->studentProfile()->create([
                'id' => \Illuminate\Support\Str::uuid(),
                'full_name' => $user->email,
                'university' => '',
                'major' => '',
                'location' => '',
                'skills' => json_encode([]),
                'interests' => json_encode([]),
                'experience' => json_encode([]),
                'education' => json_encode([]),
                'portfolio' => '',
                'avatar' => '',
                'resume' => '',
            ]);
        }

        // Siapkan data untuk diupdate
        $updateData = [
            'full_name' => $request->name ?? $profile->full_name,
        ];

        if ($request->has('university')) {
            $updateData['university'] = $request->university;
        }
        if ($request->has('major')) {
            $updateData['major'] = $request->major;
        }
        if ($request->has('location')) {
            $updateData['location'] = $request->location;
        }
        if ($request->has('skills')) {
            $updateData['skills'] = json_encode($request->skills);
        }
        if ($request->has('interests')) {
            $updateData['interests'] = json_encode($request->interests);
        }
        if ($request->has('experience')) {
            $updateData['experience'] = json_encode($request->experience);
        }
        if ($request->has('education')) {
            $updateData['education'] = json_encode($request->education);
        }
        if ($request->has('portfolio')) {
            $updateData['portfolio'] = $request->portfolio;
        }
        if ($request->has('avatar')) {
            $updateData['avatar'] = $request->avatar;
        }

        // Update data profil
        $profile->update($updateData);

        // Jika email diubah, update di tabel users juga
        if ($request->has('email') && $request->email !== $user->email) {
            $user->update(['email' => $request->email]);
        }

        // Format data untuk response
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

        return response()->json([
            'message' => 'Profile updated successfully',
            'profile' => $profileData
        ]);
    }
}
