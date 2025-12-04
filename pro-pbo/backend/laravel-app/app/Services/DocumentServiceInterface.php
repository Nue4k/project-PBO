<?php

namespace App\Services;

interface DocumentServiceInterface
{
    public function getStudentDocuments($user);
    
    public function uploadDocument(array $data, $user);
    
    public function getDocumentById(string $id, $user);
    
    public function updateDocument(string $id, array $data, $user);
    
    public function deleteDocument(string $id, $user);
}