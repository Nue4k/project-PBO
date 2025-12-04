<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\JobServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    protected JobServiceInterface $jobService;

    public function __construct(JobServiceInterface $jobService)
    {
        $this->jobService = $jobService;
    }

    /**
     * Get all active jobs (internships) for students to browse.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $result = $this->jobService->getAllJobs();
            return response()->json($result);
        } catch (\Exception $e) {
            \Log::error('Failed to fetch jobs: ' . $e->getMessage() . ' on line ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch jobs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get jobs posted by the authenticated company.
     *
     * @return JsonResponse
     */
    public function getCompanyJobs(): JsonResponse
    {
        try {
            $result = $this->jobService->getCompanyJobs();
            return response()->json($result);
        } catch (\Exception $e) {
            \Log::error('Failed to fetch company jobs: ' . $e->getMessage() . ' in file ' . $e->getFile() . ' at line ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch company jobs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created job in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'title' => 'required|string|max:150',
                'description' => 'required|string',
                'duration' => 'required|string',
                'location' => 'required|string|max:100',
                'jobType' => 'required|in:wfo,wfh,hybrid',
                'closingDate' => 'required|date|after:today',
                'isPaid' => 'required|boolean',
                'salary' => 'nullable|string',
                'requirements' => 'required|array',
                'requirements.majors' => 'array',
                'requirements.skills' => 'array',
                'requirements.gpa' => 'nullable|string',
                'requirements.other' => 'nullable|string',
                'requirements.minSemester' => 'nullable|string'
            ]);

            $user = Auth::user();
            $jobData = $request->all();
            
            $result = $this->jobService->createJob($jobData, $user);
            return response()->json($result);

        } catch (\Exception $e) {
            \Log::error('Store job error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create job: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified job.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $result = $this->jobService->getJobById($id);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch job: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified job in storage.
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $request->validate([
                'title' => 'sometimes|required|string|max:150',
                'description' => 'sometimes|required|string',
                'duration' => 'sometimes|required|string',
                'location' => 'sometimes|required|string|max:100',
                'jobType' => 'sometimes|required|in:wfo,wfh,hybrid',
                'closingDate' => 'sometimes|required|date|after:today',
                'isPaid' => 'sometimes|required|boolean',
                'is_active' => 'sometimes|required|boolean',
                'salary' => 'nullable|string',
                'requirements' => 'sometimes|required|array',
                'requirements.majors' => 'array',
                'requirements.skills' => 'array',
                'requirements.gpa' => 'nullable|string',
                'requirements.other' => 'nullable|string',
                'requirements.minSemester' => 'nullable|string'
            ]);

            $user = Auth::user();
            $jobData = $request->all();
            
            $result = $this->jobService->updateJob($id, $jobData, $user);
            return response()->json($result);

        } catch (\Exception $e) {
            \Log::error('Update job error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update job: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified job from storage.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $result = $this->jobService->deleteJob($id, $user);
            return response()->json($result);
        } catch (\Exception $e) {
            \Log::error('Delete job error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete job: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Close/deactivate the specified job.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function closeJob(string $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $result = $this->jobService->closeJob($id, $user);
            return response()->json($result);

        } catch (\Exception $e) {
            \Log::error('Close job error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to close job: ' . $e->getMessage()
            ], 500);
        }
    }
}