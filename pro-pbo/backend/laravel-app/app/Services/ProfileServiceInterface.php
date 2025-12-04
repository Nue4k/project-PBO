<?php

namespace App\Services;

interface ProfileServiceInterface
{
    public function getStudentProfile($user);
    
    public function updateStudentProfile(array $data, $user);
    
    public function getCompanyProfile($user);
    
    public function updateCompanyProfile(array $data, $user);
}