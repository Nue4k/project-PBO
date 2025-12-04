<?php

namespace App\Services;

use App\Repositories\DocumentRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentService implements DocumentServiceInterface
{
    protected DocumentRepositoryInterface $documentRepository;

    public function __construct(DocumentRepositoryInterface $documentRepository)
    {
        $this->documentRepository = $documentRepository;
    }

    public function getStudentDocuments($user)
    {
        if (!$user || strtolower($user->role) !== 'student') {
            \Log::warning('Unauthorized access attempt in DocumentService. User ID: ' . ($user ? $user->id : 'null') . ', Role: ' . ($user ? $user->role : 'null'));
            throw new \Exception('Unauthorized access');
        }

        $studentProfile = $user->studentProfile;

        if (!$studentProfile) {
            return [
                'success' => true,
                'data' => [],
                'message' => 'No documents found'
            ];
        }

        $documents = $this->documentRepository->getDocumentsByStudentId($studentProfile->id);

        $formattedDocuments = $documents->map(function ($document) {
            return [
                'id' => $document->id,
                'title' => $document->title,
                'file_url' => Storage::url($document->file_url),
                'file_type' => $document->file_type,
                'created_at' => $document->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $document->updated_at->format('Y-m-d H:i:s')
            ];
        });

        return [
            'success' => true,
            'data' => $formattedDocuments,
            'count' => $formattedDocuments->count()
        ];
    }

    public function uploadDocument(array $data, $user)
    {
        if (!$user || strtolower($user->role) !== 'student') {
            \Log::warning('Unauthorized access attempt in DocumentService. User ID: ' . ($user ? $user->id : 'null') . ', Role: ' . ($user ? $user->role : 'null'));
            throw new \Exception('Unauthorized access');
        }

        $studentProfile = $user->studentProfile;

        if (!$studentProfile) {
            throw new \Exception('Student profile not found');
        }

        if (!isset($data['file']) || !$data['file'] instanceof UploadedFile) {
            throw new \Exception('No valid file provided');
        }

        $file = $data['file'];
        $originalName = $file->getClientOriginalName();
        $fileType = $file->getClientOriginalExtension();
        $mimeType = $file->getMimeType();

        // Validate file type
        $allowedTypes = ['pdf', 'doc', 'docx', 'txt', 'rtf'];
        if (!in_array(strtolower($fileType), $allowedTypes)) {
            throw new \Exception('File type not allowed');
        }

        // Validate file size (e.g., 10MB max)
        if ($file->getSize() > 10 * 1024 * 1024) {
            throw new \Exception('File size too large');
        }

        // Generate unique filename
        $filename = Str::uuid() . '.' . $fileType;
        $path = $file->storeAs('documents/' . $studentProfile->id, $filename, 'public');

        $documentData = [
            'id' => Str::uuid(),
            'student_id' => $studentProfile->id,
            'title' => $data['title'] ?? pathinfo($originalName, PATHINFO_FILENAME),
            'file_url' => $path,
            'file_type' => $mimeType
        ];

        $document = $this->documentRepository->create($documentData);

        return [
            'success' => true,
            'message' => 'Document uploaded successfully',
            'data' => [
                'id' => $document->id,
                'title' => $document->title,
                'file_url' => Storage::url($document->file_url),
                'file_type' => $document->file_type,
                'created_at' => $document->created_at->format('Y-m-d H:i:s')
            ]
        ];
    }

    public function getDocumentById(string $id, $user)
    {
        if (!$user || strtolower($user->role) !== 'student') {
            \Log::warning('Unauthorized access attempt in DocumentService. User ID: ' . ($user ? $user->id : 'null') . ', Role: ' . ($user ? $user->role : 'null'));
            throw new \Exception('Unauthorized access');
        }

        $studentProfile = $user->studentProfile;

        if (!$studentProfile) {
            throw new \Exception('Student profile not found');
        }

        $document = $this->documentRepository->findById($id);

        if (!$document || $document->student_id !== $studentProfile->id) {
            throw new \Exception('Document not found or access denied');
        }

        return [
            'success' => true,
            'data' => [
                'id' => $document->id,
                'title' => $document->title,
                'file_url' => Storage::url($document->file_url),
                'file_type' => $document->file_type,
                'created_at' => $document->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $document->updated_at->format('Y-m-d H:i:s')
            ]
        ];
    }

    public function updateDocument(string $id, array $data, $user)
    {
        if (!$user || strtolower($user->role) !== 'student') {
            \Log::warning('Unauthorized access attempt in DocumentService. User ID: ' . ($user ? $user->id : 'null') . ', Role: ' . ($user ? $user->role : 'null'));
            throw new \Exception('Unauthorized access');
        }

        $studentProfile = $user->studentProfile;

        if (!$studentProfile) {
            throw new \Exception('Student profile not found');
        }

        $document = $this->documentRepository->findById($id);

        if (!$document || $document->student_id !== $studentProfile->id) {
            throw new \Exception('Document not found or access denied');
        }

        $updateData = [];
        if (isset($data['title'])) {
            $updateData['title'] = $data['title'];
        }

        $this->documentRepository->update($id, $updateData);

        $updatedDocument = $this->documentRepository->findById($id);

        return [
            'success' => true,
            'message' => 'Document updated successfully',
            'data' => [
                'id' => $updatedDocument->id,
                'title' => $updatedDocument->title,
                'file_url' => Storage::url($updatedDocument->file_url),
                'file_type' => $updatedDocument->file_type,
                'created_at' => $updatedDocument->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $updatedDocument->updated_at->format('Y-m-d H:i:s')
            ]
        ];
    }

    public function deleteDocument(string $id, $user)
    {
        if (!$user || strtolower($user->role) !== 'student') {
            \Log::warning('Unauthorized access attempt in DocumentService. User ID: ' . ($user ? $user->id : 'null') . ', Role: ' . ($user ? $user->role : 'null'));
            throw new \Exception('Unauthorized access');
        }

        $studentProfile = $user->studentProfile;

        if (!$studentProfile) {
            throw new \Exception('Student profile not found');
        }

        $document = $this->documentRepository->findById($id);

        if (!$document || $document->student_id !== $studentProfile->id) {
            throw new \Exception('Document not found or access denied');
        }

        // Delete the actual file from storage
        if (Storage::disk('public')->exists($document->file_url)) {
            Storage::disk('public')->delete($document->file_url);
        }

        $this->documentRepository->delete($id);

        return [
            'success' => true,
            'message' => 'Document deleted successfully'
        ];
    }
}