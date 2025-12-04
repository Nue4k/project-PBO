<?php

namespace App\Services;

use App\Repositories\JobRepositoryInterface;
use App\Models\Job;
use App\Models\CompanyProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class JobService implements JobServiceInterface
{
    protected JobRepositoryInterface $jobRepository;

    public function __construct(JobRepositoryInterface $jobRepository)
    {
        $this->jobRepository = $jobRepository;
    }

    public function getAllJobs()
    {
        $now = now();
        $jobs = $this->jobRepository->getAllActiveJobs();

        $totalJobs = $jobs->count();

        $formattedJobs = $jobs->map(function ($job) use ($now) {
            $requirements = json_decode($job->requirements, true) ?: [];

            $isExpired = $job->closing_date && $job->closing_date <= $now;
            $isActive = $job->is_active;

            return [
                'id' => $job->id,
                'title' => $job->title,
                'company' => $job->companyProfile ? $job->companyProfile->company_name : 'Unknown Company',
                'location' => $job->location ?? '',
                'type' => $job->job_type ?? '',
                'duration' => $requirements['duration'] ?? '',
                'posted' => (is_string($job->created_at) ? $job->created_at : $job->created_at->format('Y-m-d')),
                'deadline' => $job->closing_date ? (is_string($job->closing_date) ? $job->closing_date : $job->closing_date->format('Y-m-d')) : '',
                'description' => $job->description ?? '',
                'requirements' => $requirements['majors'] ?? [],
                'status' => $isExpired ? 'Closed' : 'Open',
                'tags' => $requirements['skills'] ?? [],
                'paid' => $requirements['is_paid'] ?? 'unpaid',
                'minSemester' => $requirements['min_semester'] ?? 1,
                'salary' => $requirements['salary'] ?? '',
                'isPaid' => $requirements['is_paid'] === 'paid',
                'salaryAmount' => $requirements['salary'] ?? ''
            ];
        });

        $openJobs = $formattedJobs->filter(function($job) {
            return $job['status'] === 'Open';
        })->values();

        return [
            'success' => true,
            'data' => $openJobs,
            'total_available' => $totalJobs,
            'total_open' => $openJobs->count()
        ];
    }

    public function getCompanyJobs()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'company') {
            throw new \Exception('Unauthorized access');
        }

        $companyProfile = $user->companyProfile;

        if (!$companyProfile) {
            $companyProfile = $user->companyProfile()->create([
                'id' => Str::uuid(),
                'company_name' => $user->email,
                'description' => '',
                'industry' => '',
                'website_url' => '',
                'address' => '',
                'contact_email' => $user->email,
                'contact_phone' => '',
                'logo_url' => '',
            ]);
        }

        $jobs = $this->jobRepository->getJobsByCompanyId($companyProfile->id);

        $formattedJobs = $jobs->map(function ($job) {
            $requirements = [];
            if ($job->requirements) {
                $decoded = json_decode($job->requirements, true);
                if ($decoded !== null) {
                    $requirements = $decoded;
                }
            }

            $deadline = '';
            if ($job->closing_date) {
                if (is_string($job->closing_date)) {
                    $deadline = $job->closing_date;
                } elseif ($job->closing_date instanceof \DateTime) {
                    $deadline = $job->closing_date->format('Y-m-d');
                }
            }

            $posted = '';
            if ($job->created_at) {
                if (is_string($job->created_at)) {
                    $posted = $job->created_at;
                } elseif ($job->created_at instanceof \DateTime) {
                    $posted = $job->created_at->format('Y-m-d');
                }
            }

            return [
                'id' => $job->id,
                'title' => $job->title,
                'description' => $job->description,
                'location' => $job->location ?? '',
                'type' => $job->job_type,
                'deadline' => $deadline,
                'description' => $job->description,
                'requirements' => $requirements,
                'status' => $job->is_active ? 'Active' : 'Inactive',
                'posted' => $posted,
                'applications_count' => $job->applications_count ?? 0,
                'is_active' => $job->is_active,
                'closing_date' => $deadline,
                'salary' => $requirements['salary'] ?? '',
                'isPaid' => isset($requirements['is_paid']) && $requirements['is_paid'] === 'paid',
            ];
        });

        return [
            'success' => true,
            'data' => $formattedJobs,
            'count' => $formattedJobs->count(),
            'message' => $formattedJobs->count() > 0 ? 'Jobs retrieved successfully' : 'No jobs found for this company'
        ];
    }

    public function createJob(array $data, $user)
    {
        if (!$user || $user->role !== 'company') {
            throw new \Exception('Unauthorized access');
        }

        $companyProfile = $user->companyProfile;

        if (!$companyProfile) {
            $companyProfile = $user->companyProfile()->create([
                'id' => Str::uuid(),
                'company_name' => $user->email,
                'description' => '',
                'industry' => '',
                'website_url' => '',
                'address' => '',
                'contact_email' => $user->email,
                'contact_phone' => '',
                'logo_url' => '',
            ]);
        }

        $requirements = [
            'majors' => $data['requirements']['majors'] ?? [],
            'skills' => $data['requirements']['skills'] ?? [],
            'gpa' => $data['requirements']['gpa'] ?? '',
            'other' => $data['requirements']['other'] ?? '',
            'min_semester' => $data['requirements']['minSemester'] ?? '1',
            'duration' => $data['duration'] ?? '',
            'is_paid' => $data['isPaid'] ? 'paid' : 'unpaid',
            'salary' => $data['salary'] ?? ''
        ];

        $jobData = [
            'id' => Str::uuid(),
            'company_id' => $companyProfile->id,
            'title' => $data['title'],
            'description' => $data['description'],
            'job_type' => $data['jobType'],
            'location' => $data['location'],
            'closing_date' => $data['closingDate'],
            'requirements' => json_encode($requirements),
            'is_active' => true
        ];

        $job = $this->jobRepository->create($jobData);

        $createdJob = $this->jobRepository->findById($job->id);

        return [
            'success' => true,
            'message' => 'Job created successfully',
            'data' => $createdJob
        ];
    }

    public function getJobById(string $id)
    {
        $job = $this->jobRepository->findById($id);

        if (!$job) {
            throw new \Exception('Job not found');
        }

        $requirements = json_decode($job->requirements, true) ?: [];

        $formattedJob = [
            'id' => $job->id,
            'title' => $job->title,
            'company' => $job->companyProfile->company_name ?? 'Unknown Company',
            'location' => $job->location ?? '',
            'type' => $job->job_type,
            'duration' => $requirements['duration'] ?? '',
            'posted' => (is_string($job->created_at) ? $job->created_at : $job->created_at->format('Y-m-d')),
            'deadline' => $job->closing_date ? (is_string($job->closing_date) ? $job->closing_date : $job->closing_date->format('Y-m-d')) : '',
            'description' => $job->description,
            'requirements' => $requirements['majors'] ?? [],
            'status' => $job->closing_date < now() ? 'Closed' : 'Open',
            'tags' => $requirements['skills'] ?? [],
            'paid' => $requirements['is_paid'] ?? 'unpaid',
            'minSemester' => $requirements['min_semester'] ?? 1,
            'salary' => $requirements['salary'] ?? '',
            'isPaid' => $requirements['is_paid'] === 'paid',
            'salaryAmount' => $requirements['salary'] ?? ''
        ];

        return [
            'success' => true,
            'data' => $formattedJob
        ];
    }

    public function updateJob(string $id, array $data, $user)
    {
        if (!$user || $user->role !== 'company') {
            throw new \Exception('Unauthorized access');
        }

        $job = $this->jobRepository->findById($id);

        if (!$job) {
            throw new \Exception('Job not found');
        }

        $companyProfile = $user->companyProfile;

        if (!$companyProfile) {
            $companyProfile = $user->companyProfile()->create([
                'id' => Str::uuid(),
                'company_name' => $user->email,
                'description' => '',
                'industry' => '',
                'website_url' => '',
                'address' => '',
                'contact_email' => $user->email,
                'contact_phone' => '',
                'logo_url' => '',
            ]);
        }

        if ($job->company_id !== $companyProfile->id) {
            throw new \Exception('Unauthorized to update this job');
        }

        $updateData = [];

        if (isset($data['title'])) {
            $updateData['title'] = $data['title'];
        }
        if (isset($data['description'])) {
            $updateData['description'] = $data['description'];
        }
        if (isset($data['jobType'])) {
            $updateData['job_type'] = $data['jobType'];
        }
        if (isset($data['location'])) {
            $updateData['location'] = $data['location'];
        }
        if (isset($data['closingDate'])) {
            $updateData['closing_date'] = $data['closingDate'];
        }
        if (isset($data['is_active'])) {
            $updateData['is_active'] = $data['is_active'];
        }

        if (isset($data['requirements'])) {
            $requirements = [
                'majors' => $data['requirements']['majors'] ?? [],
                'skills' => $data['requirements']['skills'] ?? [],
                'gpa' => $data['requirements']['gpa'] ?? '',
                'other' => $data['requirements']['other'] ?? '',
                'min_semester' => $data['requirements']['minSemester'] ?? '1',
                'duration' => $data['requirements']['duration'] ?? '',
                'is_paid' => $data['requirements']['isPaid'] ?? ($data['isPaid'] ? 'paid' : 'unpaid'),
                'salary' => $data['requirements']['salary'] ?? ($data['salary'] ?? '')
            ];

            $updateData['requirements'] = json_encode($requirements);
        }

        $this->jobRepository->update($id, $updateData);

        $updatedJob = $this->jobRepository->findById($id);

        return [
            'success' => true,
            'message' => 'Job updated successfully',
            'data' => $updatedJob
        ];
    }

    public function deleteJob(string $id, $user)
    {
        if (!$user || $user->role !== 'company') {
            throw new \Exception('Unauthorized access');
        }

        $job = $this->jobRepository->findById($id);

        if (!$job) {
            throw new \Exception('Job not found');
        }

        $companyProfile = $user->companyProfile;

        if (!$companyProfile) {
            $companyProfile = $user->companyProfile()->create([
                'id' => Str::uuid(),
                'company_name' => $user->email,
                'description' => '',
                'industry' => '',
                'website_url' => '',
                'address' => '',
                'contact_email' => $user->email,
                'contact_phone' => '',
                'logo_url' => '',
            ]);
        }

        if ($job->company_id !== $companyProfile->id) {
            throw new \Exception('Unauthorized to delete this job');
        }

        $this->jobRepository->delete($id);

        return [
            'success' => true,
            'message' => 'Job deleted successfully'
        ];
    }

    public function closeJob(string $id, $user)
    {
        if (!$user || $user->role !== 'company') {
            throw new \Exception('Unauthorized access');
        }

        $job = $this->jobRepository->findById($id);

        if (!$job) {
            throw new \Exception('Job not found');
        }

        $companyProfile = $user->companyProfile;

        if (!$companyProfile) {
            $companyProfile = $user->companyProfile()->create([
                'id' => Str::uuid(),
                'company_name' => $user->email,
                'description' => '',
                'industry' => '',
                'website_url' => '',
                'address' => '',
                'contact_email' => $user->email,
                'contact_phone' => '',
                'logo_url' => '',
            ]);
        }

        if ($job->company_id !== $companyProfile->id) {
            throw new \Exception('Unauthorized to close this job');
        }

        $job = $this->jobRepository->closeJob($id);

        $closedJob = $this->jobRepository->findById($id);

        return [
            'success' => true,
            'message' => 'Job closed successfully',
            'data' => $closedJob
        ];
    }
}