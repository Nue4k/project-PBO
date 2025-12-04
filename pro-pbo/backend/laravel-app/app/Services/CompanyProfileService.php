<?php

namespace App\Services;

use App\Models\CompanyProfile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CompanyProfileService
{
    /**
     * Get or create company profile for the user
     *
     * @param User $user
     * @return CompanyProfile
     */
    public function getOrCreateCompanyProfile(User $user): CompanyProfile
    {
        $profile = $user->companyProfile;
        
        if (!$profile) {
            $profile = $user->companyProfile()->create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'company_name' => $user->email, // Use email as fallback name
                'description' => '',
                'industry' => '',
                'website_url' => '',
                'address' => '',
                'contact_email' => $user->email, // Use the user's email as default
                'contact_phone' => '',
                'logo_url' => '',
            ]);
        }
        
        return $profile;
    }

    /**
     * Update company profile
     *
     * @param array $data
     * @param User $user
     * @return CompanyProfile
     */
    public function updateCompanyProfile(array $data, User $user): CompanyProfile
    {
        $profile = $this->getOrCreateCompanyProfile($user);
        
        // Prepare update data, mapping frontend field names to database column names
        $updateData = [];
        
        if (isset($data['name'])) {
            $updateData['company_name'] = $data['name'];
        }
        if (isset($data['description'])) {
            $updateData['description'] = $data['description'];
        }
        if (isset($data['industry'])) {
            $updateData['industry'] = $data['industry'];
        }
        if (isset($data['location'])) {
            $updateData['address'] = $data['location']; // Map location to address
        }
        if (isset($data['contactEmail'])) {
            $updateData['contact_email'] = $data['contactEmail']; // Map contactEmail to contact_email
        }
        if (isset($data['contactPhone'])) {
            $updateData['contact_phone'] = $data['contactPhone']; // Map contactPhone to contact_phone
        }
        if (isset($data['website'])) {
            $updateData['website_url'] = $data['website']; // Map website to website_url
        }
        if (isset($data['logo'])) {
            $updateData['logo_url'] = $data['logo'];
        }

        $profile->update($updateData);
        
        return $profile;
    }

    /**
     * Get company profile by user
     *
     * @param User $user
     * @return CompanyProfile|null
     */
    public function getCompanyProfile(User $user): ?CompanyProfile
    {
        return $user->companyProfile;
    }
}