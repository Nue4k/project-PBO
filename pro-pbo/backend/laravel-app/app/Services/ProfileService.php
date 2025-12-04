<?php

namespace App\Services;

use App\Models\StudentProfile;
use App\Models\CompanyProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\StudentProfileService;
use App\Services\CompanyProfileService;

class ProfileService implements ProfileServiceInterface
{
    protected StudentProfileService $studentProfileService;
    protected CompanyProfileService $companyProfileService;

    public function __construct(
        StudentProfileService $studentProfileService,
        CompanyProfileService $companyProfileService
    ) {
        $this->studentProfileService = $studentProfileService;
        $this->companyProfileService = $companyProfileService;
    }

    public function getStudentProfile($user)
    {
        if (!$user || $user->role !== 'student') {
            throw new \Exception('Unauthorized access');
        }

        return $this->studentProfileService->getStudentProfile($user);
    }

    public function updateStudentProfile(array $data, $user)
    {
        if (!$user || $user->role !== 'student') {
            throw new \Exception('Unauthorized access');
        }

        return $this->studentProfileService->updateStudentProfile($data, $user);
    }

    public function getCompanyProfile($user)
    {
        if (!$user || $user->role !== 'company') {
            throw new \Exception('Unauthorized access');
        }

        return $this->companyProfileService->getCompanyProfile($user);
    }

    public function updateCompanyProfile(array $data, $user)
    {
        if (!$user || $user->role !== 'company') {
            throw new \Exception('Unauthorized access');
        }

        return $this->companyProfileService->updateCompanyProfile($data, $user);
    }
}