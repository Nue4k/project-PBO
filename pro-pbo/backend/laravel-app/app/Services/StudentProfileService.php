<?php

namespace App\Services;

use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StudentProfileService
{
    /**
     * Get or create student profile for the user
     *
     * @param User $user
     * @return StudentProfile
     */
    public function getOrCreateStudentProfile(User $user): StudentProfile
    {
        $profile = $user->studentProfile;

        if (!$profile) {
            $profile = $user->studentProfile()->create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'full_name' => $user->email, // Use email as fallback name
                'email' => $user->email, // Also set email
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
                'gpa' => 0.0,
                'graduation_year' => null,
                'status' => 'undergraduate',
                'bio' => '',
                'phone_number' => '',
                'linkedin_url' => '',
            ]);
        }

        return $profile;
    }

    /**
     * Update student profile
     *
     * @param array $data
     * @param User $user
     * @return StudentProfile
     */
    public function updateStudentProfile(array $data, User $user): StudentProfile
    {
        $profile = $this->getOrCreateStudentProfile($user);

        // Prepare update data
        $updateData = [];

        if (isset($data['name'])) {
            $updateData['full_name'] = $data['name'];
        }
        if (isset($data['email']) && $data['email'] !== $user->email) {
            $updateData['email'] = $data['email'];
            // Also update the user's email
            $user->update(['email' => $data['email']]);
        }
        if (isset($data['university'])) {
            $updateData['university'] = $data['university'];
        }
        if (isset($data['major'])) {
            $updateData['major'] = $data['major'];
        }
        if (isset($data['location'])) {
            $updateData['location'] = $data['location'];
        }
        if (isset($data['skills'])) {
            $updateData['skills'] = json_encode($data['skills']);
        }
        if (isset($data['interests'])) {
            $updateData['interests'] = json_encode($data['interests']);
        }
        if (isset($data['experience'])) {
            $updateData['experience'] = json_encode($data['experience']);
        }
        if (isset($data['education'])) {
            $updateData['education'] = json_encode($data['education']);
        }
        if (isset($data['portfolio'])) {
            $updateData['portfolio'] = $data['portfolio'];
        }
        if (isset($data['avatar'])) {
            $updateData['avatar'] = $data['avatar'];
        }

        $profile->update($updateData);

        return $profile;
    }

    /**
     * Get student profile by user
     *
     * @param User $user
     * @return StudentProfile|null
     */
    public function getStudentProfile(User $user): ?StudentProfile
    {
        return $user->studentProfile;
    }
}