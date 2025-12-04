<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DocumentServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    protected DocumentServiceInterface $documentService;

    public function __construct(DocumentServiceInterface $documentService)
    {
        $this->documentService = $documentService;
    }

    /**
     * Get all documents for the authenticated student.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $user = Auth::user();
            $result = $this->documentService->getStudentDocuments($user);
            return response()->json($result);
        } catch (\Exception $e) {
            \Log::error('Failed to fetch documents: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch documents: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created document in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();

            if (!$user || $user->role !== 'student') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 401);
            }

            $request->validate([
                'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240', // 10MB max
                'title' => 'nullable|string|max:100',
            ]);

            $documentData = [
                'file' => $request->file('file'),
                'title' => $request->title ?? null
            ];

            $result = $this->documentService->uploadDocument($documentData, $user);
            return response()->json($result, 201);

        } catch (\Exception $e) {
            \Log::error('Failed to upload document: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload document: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified document.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $result = $this->documentService->getDocumentById($id, $user);
            return response()->json($result);

        } catch (\Exception $e) {
            \Log::error('Failed to fetch document: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch document: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Serve a document file for viewing/downloading.
     *
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\JsonResponse
     */
    public function serve(string $id)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 401);
            }

            // Get the student profile for students
            $studentProfile = $user->studentProfile;

            // Find the document
            $document = null;
            if ($studentProfile) {
                // Student accessing their own document
                try {
                    $documentResult = $this->documentService->getDocumentById($id, $user);
                    if (!$documentResult['success']) {
                        return response()->json([
                            'success' => false,
                            'message' => $documentResult['message']
                        ], 404);
                    }
                    // Get document from database for file access
                    $document = \App\Models\Document::find($id);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Document not found or access denied'
                    ], 404);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }

            if (!$document) {
                return response()->json([
                    'success' => false,
                    'message' => 'Document not found'
                ], 404);
            }

            // Log document file path for debugging
            \Log::info('Attempting to serve document - Path: ' . $document->file_url . ' for document ID: ' . $id);

            // Check if file exists in the public storage disk
            $fileExists = \Storage::disk('public')->exists($document->file_url);
            \Log::info('File existence result: ' . ($fileExists ? 'FOUND' : 'NOT FOUND') . ' for path: ' . $document->file_url);

            if (!$fileExists) {
                // Additional check: physical file path
                $physicalPath = storage_path('app/public/' . $document->file_url);
                $physicalExists = file_exists($physicalPath);
                \Log::info('Physical file existence: ' . ($physicalExists ? 'FOUND' : 'NOT FOUND') . ' at: ' . $physicalPath);

                return response()->json([
                    'success' => false,
                    'message' => 'File not found on disk'
                ], 404);
            }

            // Stream the file content directly from the public disk
            $fileContents = \Storage::disk('public')->get($document->file_url);
            $mimeType = \Storage::disk('public')->mimeType($document->file_url) ?: 'application/octet-stream';
            \Log::info('Successfully retrieved file contents for document ID: ' . $id . ' with MIME type: ' . $mimeType);

            // Create a response with the file content
            $response = response($fileContents);
            $response->header('Content-Type', $mimeType);
            $response->header('Content-Disposition', 'inline; filename="' . basename($document->file_url) . '"');

            return $response;

        } catch (\Exception $e) {
            \Log::error('Failed to serve document: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to serve document: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified document in storage.
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $user = Auth::user();

            if (!$user || $user->role !== 'student') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 401);
            }

            $request->validate([
                'title' => 'nullable|string|max:100',
            ]);

            $documentData = [
                'title' => $request->title
            ];

            $result = $this->documentService->updateDocument($id, $documentData, $user);
            return response()->json($result);

        } catch (\Exception $e) {
            \Log::error('Failed to update document: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update document: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified document from storage.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $result = $this->documentService->deleteDocument($id, $user);
            return response()->json($result);

        } catch (\Exception $e) {
            \Log::error('Failed to delete document: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete document: ' . $e->getMessage()
            ], 500);
        }
    }
}