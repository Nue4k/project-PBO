<?php

namespace App\Services;

interface ApplicationServiceInterface
{
    public function getStudentApplications($user);
    
    public function createApplication(array $data, $user);
    
    public function getApplicationById(string $id, $user);
    
    public function updateApplication(string $id, array $data, $user);
    
    public function deleteApplication(string $id, $user);
    
    public function getCompanyApplications($user);
    
    public function setInterviewSchedule(string $id, array $data, $user);
    
    public function confirmAttendance(string $id, $user);
    
    public function updateStatus(string $id, array $data, $user);
}