<?php

namespace App\Repositories;

interface ApplicationRepositoryInterface
{
    public function getApplicationsByStudentId(string $studentId);
    
    public function getApplicationsByJobIds(array $jobIds);
    
    public function findById(string $id);
    
    public function create(array $data);
    
    public function update(string $id, array $data);
    
    public function delete(string $id);
}