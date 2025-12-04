<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ApplicationServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    protected ApplicationServiceInterface $applicationService;

    public function __construct(ApplicationServiceInterface $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    /**
     * Get applications submitted by the authenticated student.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $user = Auth::user();
            $result = $this->applicationService->getStudentApplications($user);
            return response()->json($result);
        } catch (\Exception $e) {
            \Log::error('Failed to fetch student applications: ' . $e->getMessage() . ' on line ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch applications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit a new application for a job.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'job_id' => 'required|exists:jobs,id',
                'cover_letter' => 'required|string',
                'portfolio_url' => 'nullable|string',
                'availability' => 'nullable|string',
                'expected_duration' => 'nullable|string',
                'additional_info' => 'nullable|string',
            ]);

            $user = Auth::user();
            $applicationData = $request->all();
            
            $result = $this->applicationService->createApplication($applicationData, $user);
            return response()->json($result);

        } catch (\Exception $e) {
            \Log::error('Failed to submit application: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified application.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $result = $this->applicationService->getApplicationById($id, $user);
            return response()->json($result);

        } catch (\Exception $e) {
            \Log::error('Failed to fetch application: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified application (for student to withdraw application, for example).
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $applicationData = $request->all();
            
            $result = $this->applicationService->updateApplication($id, $applicationData, $user);
            return response()->json($result);

        } catch (\Exception $e) {
            \Log::error('Failed to update application: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified application (delete).
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $result = $this->applicationService->deleteApplication($id, $user);
            return response()->json($result);

        } catch (\Exception $e) {
            \Log::error('Failed to delete application: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get applications for jobs posted by the authenticated company.
     *
     * @return JsonResponse
     */
    public function getCompanyApplications(): JsonResponse
    {
        try {
            $user = Auth::user();
            $result = $this->applicationService->getCompanyApplications($user);
            return response()->json($result);
        } catch (\Exception $e) {
            \Log::error('Failed to fetch company applications: ' . $e->getMessage() . ' on line ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch applications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set interview schedule for an application.
     *
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    public function setInterviewSchedule(string $id, Request $request): JsonResponse
    {
        try {
            $request->validate([
                'interview_date' => 'required|date',
                'interview_time' => 'required|date_format:H:i',
                'interview_method' => 'required|in:online,offline',
                'interview_location' => 'nullable|string',
                'interview_notes' => 'nullable|string',
            ]);

            $user = Auth::user();
            $scheduleData = $request->all();
            
            $result = $this->applicationService->setInterviewSchedule($id, $scheduleData, $user);
            return response()->json($result);

        } catch (\Exception $e) {
            \Log::error('Failed to set interview schedule: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to set interview schedule: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirm attendance for an interview.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function confirmAttendance(string $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $result = $this->applicationService->confirmAttendance($id, $user);
            return response()->json($result);

        } catch (\Exception $e) {
            \Log::error('Failed to confirm attendance: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to confirm attendance: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update application status (accept/reject).
     *
     * @param string $id
     * @return JsonResponse
     */
    public function updateStatus(string $id, Request $request): JsonResponse
    {
        try {
            $request->validate([
                'status' => 'required|in:applied,reviewed,interview,accepted,rejected',
                'feedback_note' => 'nullable|string'
            ]);

            $user = Auth::user();
            $statusData = $request->all();
            
            $result = $this->applicationService->updateStatus($id, $statusData, $user);
            return response()->json($result);

        } catch (\Exception $e) {
            \Log::error('Failed to update application status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update application status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Serve the resume document for an application
     *
     * @param string $id Application ID
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\JsonResponse
     */
    public function serveApplicationResume(string $id)
    {
        try {
            // This method handles file serving and doesn't use the service pattern
            // The original implementation is kept since it involves file handling
            // which is more complex to abstract in the service layer
            $user = Auth::user();

            if (!$user) {
                \Log::warning('Unauthorized access attempt to serve resume');
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 401);
            }

            // Check if application exists and user has access
            $application = null;

            if ($user->role === 'company') {
                \Log::info('Company user attempting to access resume - User ID: ' . $user->id);

                // Company accessing application from their posted jobs
                $companyProfile = $user->companyProfile;
                if (!$companyProfile) {
                    \Log::warning('Company profile not found for user: ' . $user->id);
                    return response()->json([
                        'success' => false,
                        'message' => 'Company profile not found'
                    ], 404);
                }

                // Get jobs posted by this company
                $companyJobs = $companyProfile->jobs()->pluck('id');
                \Log::info('Company has ' . $companyJobs->count() . ' jobs posted');

                // Find the application for one of their jobs
                $application = \App\Models\Application::whereIn('job_id', $companyJobs)
                    ->where('id', $id)
                    ->with('resume') // Load the resume relationship
                    ->first();
            } elseif ($user->role === 'student') {
                \Log::info('Student user attempting to access resume - User ID: ' . $user->id);

                // Student accessing their own application
                $studentProfile = $user->studentProfile;
                if (!$studentProfile) {
                    \Log::warning('Student profile not found for user: ' . $user->id);
                    return response()->json([
                        'success' => false,
                        'message' => 'Student profile not found'
                    ], 404);
                }

                $application = \App\Models\Application::where('student_id', $studentProfile->id)
                    ->where('id', $id)
                    ->with('resume') // Load the resume relationship
                    ->first();
            } else {
                \Log::warning('Invalid role attempting to access resume: ' . $user->role);
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }

            if (!$application) {
                \Log::warning('Application not found or access denied - ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Application not found or access denied'
                ], 404);
            }

            // Check if application has a resume
            if (!$application->resume) {
                \Log::warning('Resume not found for application ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Resume not found for this application'
                ], 404);
            }

            // Log the resume file details
            \Log::info('Resume file found - Path: ' . $application->resume->file_url . ', Type: ' . ($application->resume->file_type ?? 'unknown'));

            // Check if file exists in the public storage disk
            $fileExists = \Storage::disk('public')->exists($application->resume->file_url);

            if (!$fileExists) {
                \Log::warning('File does not exist in public disk at path: ' . $application->resume->file_url . ' for application ID: ' . $id);

                // Get list of actual files in the document location to debug
                $expectedDir = dirname($application->resume->file_url);
                if ($expectedDir && \Storage::disk('public')->exists($expectedDir)) {
                    $filesInDir = \Storage::disk('public')->files($expectedDir);
                    \Log::info('Files in expected directory "' . $expectedDir . '": ' . implode(', ', $filesInDir));
                } else {
                    \Log::warning('Expected directory "' . $expectedDir . '" does not exist in public disk');
                }

                // Update the application record to clear the resume_id pointing to missing file
                $application->resume_id = null;
                $application->save();

                return response()->json([
                    'success' => false,
                    'message' => 'Resume file has been removed or is unavailable'
                ], 404);
            }

            // Stream the file content directly from the public disk
            try {
                $fileContents = \Storage::disk('public')->get($application->resume->file_url);
                $mimeType = \Storage::disk('public')->mimeType($application->resume->file_url) ?: 'application/octet-stream';

                // Create a response with the file content
                $response = response($fileContents);
                $response->header('Content-Type', $mimeType);
                $response->header('Content-Disposition', 'inline; filename="' . basename($application->resume->file_url) . '"');

                return $response;
            } catch (\Exception $e) {
                \Log::error('Error reading file content: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Error reading file content: ' . $e->getMessage()
                ], 500);
            }

        } catch (\Exception $e) {
            \Log::error('Failed to serve application resume: ' . $e->getMessage() . ' on line ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Failed to serve resume: ' . $e->getMessage()
            ], 500);
        }
    }
}