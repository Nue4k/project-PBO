<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface JobServiceInterface
{
    public function getAllJobs();
    
    public function getCompanyJobs();
    
    public function createJob(array $data, $user);
    
    public function getJobById(string $id);
    
    public function updateJob(string $id, array $data, $user);
    
    public function deleteJob(string $id, $user);
    
    public function closeJob(string $id, $user);
}