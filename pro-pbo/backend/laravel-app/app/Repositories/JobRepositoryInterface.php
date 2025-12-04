<?php

namespace App\Repositories;

interface JobRepositoryInterface
{
    public function getAllActiveJobs();
    
    public function getJobsByCompanyId(string $companyId);
    
    public function findById(string $id);
    
    public function create(array $data);
    
    public function update(string $id, array $data);
    
    public function delete(string $id);
    
    public function closeJob(string $id);
}