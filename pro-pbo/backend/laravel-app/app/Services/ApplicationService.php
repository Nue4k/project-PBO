<?php

namespace App\Services;

use App\Repositories\ApplicationRepositoryInterface;
use App\Repositories\JobRepositoryInterface;
use App\Models\Application;
use App\Models\Job;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ApplicationService implements ApplicationServiceInterface
{
    protected ApplicationRepositoryInterface $applicationRepository;
    protected JobRepositoryInterface $jobRepository;

    public function __construct(
        ApplicationRepositoryInterface $applicationRepository,
        JobRepositoryInterface $jobRepository
    ) {
        $this->applicationRepository = $applicationRepository;
        $this->jobRepository = $jobRepository;
    }

    public function getStudentApplications($user)
    {
        if (!$user || $user->role !== 'student') {
            throw new \Exception('Unauthorized access');
        }

        $studentProfile = $user->studentProfile;

        if (!$studentProfile) {
            return [
                'success' => true,
                'data' => [],
                'message' => 'No applications found'
            ];
        }

        $applications = $this->applicationRepository->getApplicationsByStudentId($studentProfile->id);

        $formattedApplications = $applications->map(function ($application) {
            $job = $application->job;
            $companyProfile = $job->companyProfile;

            return [
                'id' => $application->id,
                'job_id' => $job->id,
                'title' => $job->title,
                'company' => $companyProfile ? $companyProfile->company_name : 'Unknown Company',
                'position' => $job->title,
                'appliedDate' => is_string($application->created_at) ? $application->created_at : $application->created_at->format('Y-m-d'),
                'status' => ucfirst($application->status),
                'deadline' => $job->closing_date ? (is_string($job->closing_date) ? $job->closing_date : $job->closing_date->format('Y-m-d')) : '',
                'description' => $job->description ?? '',
                'requirements' => json_decode($job->requirements ?? '{}', true)['majors'] ?? [],
                'statusDate' => is_string($application->updated_at) ? $application->updated_at : $application->updated_at->format('Y-m-d'),
                'feedback_note' => $application->feedback_note ?? '',
                'cover_letter' => $application->cover_letter ?? '',
                'portfolio_url' => $application->portfolio_url ?? '',
                'availability' => $application->availability ?? '',
                'expected_duration' => $application->expected_duration ?? '',
                'additional_info' => $application->additional_info ?? '',
                'interview_date' => $application->interview_date,
                'interview_time' => $application->interview_time,
                'interview_method' => $application->interview_method,
                'interview_location' => $application->interview_location,
                'interview_notes' => $application->interview_notes,
                'attendance_confirmed' => $application->attendance_confirmed,
                'attendance_confirmed_at' => $application->attendance_confirmed_at,
                'attendance_confirmation_method' => $application->attendance_confirmation_method,
                'resume_id' => $application->resume_id,
                'resume_name' => $application->resume ? $application->resume->title : ($application->resume_id ? 'Resume Dokumen Tidak Ditemukan' : null),
                'resume_type' => $application->resume ? $application->resume->file_type : null,
                'resume_url' => $application->resume ? url('storage/' . $application->resume->file_url) : null
            ];
        });

        return [
            'success' => true,
            'data' => $formattedApplications,
            'count' => $formattedApplications->count()
        ];
    }

    public function createApplication(array $data, $user)
    {
        if (!$user || $user->role !== 'student') {
            throw new \Exception('Unauthorized access');
        }

        $studentProfile = $user->studentProfile;

        if (!$studentProfile) {
            $studentProfile = $user->studentProfile()->create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'full_name' => $user->email,
                'university' => '',
                'major' => '',
                'gpa' => 0.0,
                'graduation_year' => null,
                'status' => 'undergraduate',
                'bio' => '',
                'phone_number' => '',
                'linkedin_url' => '',
                'skills' => '[]',
                'interests' => '[]',
                'experience' => '[]',
                'education' => '[]',
                'portfolio' => '',
                'avatar' => '',
                'location' => '',
                'resume' => ''
            ]);
        }

        $job = $this->jobRepository->findById($data['job_id']);
        if (!$job) {
            throw new \Exception('Job not found');
        }

        $existingApplication = Application::where([
            'job_id' => $data['job_id'],
            'student_id' => $studentProfile->id
        ])->first();

        if ($existingApplication) {
            throw new \Exception('You have already applied for this job');
        }

        $applicationData = [
            'id' => Str::uuid(),
            'job_id' => $data['job_id'],
            'student_id' => $studentProfile->id,
            'resume_id' => $data['resume_id'] ?? null,
            'cover_letter' => $data['cover_letter'],
            'portfolio_url' => $data['portfolio_url'] ?? null,
            'availability' => $data['availability'] ?? null,
            'expected_duration' => $data['expected_duration'] ?? null,
            'additional_info' => $data['additional_info'] ?? null,
            'status' => 'applied'
        ];

        $application = $this->applicationRepository->create($applicationData);

        return [
            'success' => true,
            'message' => 'Application submitted successfully',
            'data' => $application
        ];
    }

    public function getApplicationById(string $id, $user)
    {
        if (!$user || $user->role !== 'student') {
            throw new \Exception('Unauthorized access');
        }

        $studentProfile = $user->studentProfile;

        if (!$studentProfile) {
            throw new \Exception('Student profile not found');
        }

        $application = $this->applicationRepository->findById($id);

        if (!$application || $application->student_id !== $studentProfile->id) {
            throw new \Exception('Application not found');
        }

        $job = $application->job;
        $companyProfile = $job->companyProfile;

        $formattedApplication = [
            'id' => $application->id,
            'job_id' => $job->id,
            'title' => $job->title,
            'company' => $companyProfile ? $companyProfile->company_name : 'Unknown Company',
            'position' => $job->title,
            'appliedDate' => is_string($application->created_at) ? $application->created_at : $application->created_at->format('Y-m-d'),
            'status' => ucfirst($application->status),
            'deadline' => $job->closing_date ? (is_string($job->closing_date) ? $job->closing_date : $job->closing_date->format('Y-m-d')) : '',
            'description' => $job->description ?? '',
            'requirements' => json_decode($job->requirements ?? '{}', true)['majors'] ?? [],
            'statusDate' => is_string($application->updated_at) ? $application->updated_at : $application->updated_at->format('Y-m-d'),
            'feedback_note' => $application->feedback_note ?? '',
            'cover_letter' => $application->cover_letter ?? '',
            'portfolio_url' => $application->portfolio_url ?? '',
            'availability' => $application->availability ?? '',
            'expected_duration' => $application->expected_duration ?? '',
            'additional_info' => $application->additional_info ?? '',
            'resume_id' => $application->resume_id,
            'resume_name' => $application->resume ? $application->resume->title : ($application->resume_id ? 'Resume Dokumen Tidak Ditemukan' : null),
            'resume_type' => $application->resume ? $application->resume->file_type : null,
            'resume_url' => $application->resume ? url('storage/' . $application->resume->file_url) : null
        ];

        return [
            'success' => true,
            'data' => $formattedApplication
        ];
    }

    public function updateApplication(string $id, array $data, $user)
    {
        if (!$user || $user->role !== 'student') {
            throw new \Exception('Unauthorized access');
        }

        $studentProfile = $user->studentProfile;

        if (!$studentProfile) {
            throw new \Exception('Student profile not found');
        }

        $application = $this->applicationRepository->findById($id);

        if (!$application || $application->student_id !== $studentProfile->id) {
            throw new \Exception('Application not found');
        }

        $status = $data['status'] ?? null;
        if ($status === 'withdrawn') {
            $this->applicationRepository->update($id, ['status' => $status]);
        }

        return [
            'success' => true,
            'message' => 'Application updated successfully',
            'data' => $application
        ];
    }

    public function deleteApplication(string $id, $user)
    {
        if (!$user || $user->role !== 'student') {
            throw new \Exception('Unauthorized access');
        }

        $studentProfile = $user->studentProfile;

        if (!$studentProfile) {
            throw new \Exception('Student profile not found');
        }

        $application = $this->applicationRepository->findById($id);

        if (!$application || $application->student_id !== $studentProfile->id) {
            throw new \Exception('Application not found');
        }

        $this->applicationRepository->delete($id);

        return [
            'success' => true,
            'message' => 'Application withdrawn successfully'
        ];
    }

    public function getCompanyApplications($user)
    {
        if (!$user || $user->role !== 'company') {
            throw new \Exception('Unauthorized access');
        }

        $companyProfile = $user->companyProfile;

        if (!$companyProfile) {
            return [
                'success' => true,
                'data' => [],
                'message' => 'No applications found'
            ];
        }

        $companyJobIds = $companyProfile->jobs()->pluck('id')->toArray();

        $applications = $this->applicationRepository->getApplicationsByJobIds($companyJobIds);

        $formattedApplications = $applications->map(function ($application) {
            $studentProfile = $application->studentProfile;
            $job = $application->job;
            $student = $studentProfile->user;

            return [
                'id' => $application->id,
                'job_id' => $job->id,
                'job_title' => $job->title,
                'student_id' => $studentProfile->id,
                'student_name' => $studentProfile->full_name ?? $student->email,
                'student_email' => $student->email,
                'applied_date' => is_string($application->created_at) ? $application->created_at : $application->created_at->format('Y-m-d'),
                'status' => ucfirst($application->status),
                'feedback_note' => $application->feedback_note ?? '',
                'location' => $job->location ?? '',
                'job_type' => $job->job_type ?? '',
                'description' => $job->description ?? '',
                'cover_letter' => $application->cover_letter ?? '',
                'portfolio_url' => $application->portfolio_url ?? '',
                'availability' => $application->availability ?? '',
                'expected_duration' => $application->expected_duration ?? '',
                'additional_info' => $application->additional_info ?? '',
                'interview_date' => $application->interview_date,
                'interview_time' => $application->interview_time,
                'interview_method' => $application->interview_method,
                'interview_location' => $application->interview_location,
                'interview_notes' => $application->interview_notes,
                'attendance_confirmed' => $application->attendance_confirmed,
                'attendance_confirmed_at' => $application->attendance_confirmed_at,
                'attendance_confirmation_method' => $application->attendance_confirmation_method,
                'resume_id' => $application->resume_id,
                'resume_name' => $application->resume ? $application->resume->title : ($application->resume_id ? 'Resume Dokumen Tidak Ditemukan' : null),
                'resume_type' => $application->resume ? $application->resume->file_type : null,
                'resume_url' => $application->resume ? url('storage/' . $application->resume->file_url) : null,
            ];
        });

        return [
            'success' => true,
            'data' => $formattedApplications,
            'count' => $formattedApplications->count()
        ];
    }

    public function setInterviewSchedule(string $id, array $data, $user)
    {
        if (!$user || $user->role !== 'company') {
            throw new \Exception('Unauthorized access');
        }

        $companyProfile = $user->companyProfile;

        if (!$companyProfile) {
            throw new \Exception('Company profile not found');
        }

        $application = $this->applicationRepository->findById($id);

        if (!$application) {
            throw new \Exception('Application not found');
        }

        if ($application->job->companyProfile->id !== $companyProfile->id) {
            throw new \Exception('Unauthorized to set interview schedule for this application');
        }

        $updateData = [
            'interview_date' => $data['interview_date'],
            'interview_time' => $data['interview_time'],
            'interview_method' => $data['interview_method'],
            'interview_location' => $data['interview_location'] ?? null,
            'interview_notes' => $data['interview_notes'] ?? null,
            'status' => 'interview'
        ];

        DB::transaction(function () use ($updateData, $application) {
            $application->update($updateData);
        });

        $application->refresh();

        return [
            'success' => true,
            'message' => 'Interview schedule set successfully',
            'data' => $application
        ];
    }

    public function confirmAttendance(string $id, $user)
    {
        if (!$user || $user->role !== 'student') {
            throw new \Exception('Unauthorized access');
        }

        $studentProfile = $user->studentProfile;

        if (!$studentProfile) {
            throw new \Exception('Student profile not found');
        }

        $application = $studentProfile->applications()->where('id', $id)->first();

        if (!$application) {
            throw new \Exception('Application not found or does not belong to you');
        }

        if (!$application->interview_date) {
            throw new \Exception('No interview scheduled for this application');
        }

        $application->update([
            'attendance_confirmed' => true,
            'attendance_confirmed_at' => now(),
            'attendance_confirmation_method' => 'system'
        ]);

        return [
            'success' => true,
            'message' => 'Attendance confirmed successfully',
            'data' => [
                'attendance_confirmed' => $application->attendance_confirmed,
                'attendance_confirmed_at' => $application->attendance_confirmed_at,
                'attendance_confirmation_method' => $application->attendance_confirmation_method
            ]
        ];
    }

    public function updateStatus(string $id, array $data, $user)
    {
        if (!$user || $user->role !== 'company') {
            throw new \Exception('Unauthorized access');
        }

        $companyProfile = $user->companyProfile;

        if (!$companyProfile) {
            throw new \Exception('Company profile not found');
        }

        $application = $this->applicationRepository->findById($id);

        if (!$application) {
            throw new \Exception('Application not found');
        }

        if ($application->job->companyProfile->id !== $companyProfile->id) {
            throw new \Exception('Unauthorized to update status for this application');
        }

        $updateData = [
            'status' => $data['status'],
            'feedback_note' => $data['feedback_note'] ?? $application->feedback_note
        ];

        DB::transaction(function () use ($updateData, $application) {
            $application->update($updateData);
        });

        $application->refresh();

        return [
            'success' => true,
            'message' => 'Application status updated successfully',
            'data' => $application
        ];
    }
}