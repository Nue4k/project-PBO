<?php

namespace App\Repositories;

use App\Models\Application;
use Illuminate\Support\Str;

class ApplicationRepository implements ApplicationRepositoryInterface
{
    public function getApplicationsByStudentId(string $studentId)
    {
        return Application::where('student_id', $studentId)
            ->with(['job', 'job.companyProfile', 'resume'])
            ->get();
    }

    public function getApplicationsByJobIds(array $jobIds)
    {
        return Application::whereIn('job_id', $jobIds)
            ->with(['studentProfile.user', 'job', 'resume'])
            ->get();
    }

    public function findById(string $id)
    {
        return Application::with('job.companyProfile')->find($id);
    }

    public function create(array $data)
    {
        $data['id'] = $data['id'] ?? Str::uuid();
        return Application::create($data);
    }

    public function update(string $id, array $data)
    {
        $application = Application::find($id);
        if ($application) {
            $application->update($data);
        }
        return $application;
    }

    public function delete(string $id)
    {
        $application = Application::find($id);
        if ($application) {
            return $application->delete();
        }
        return false;
    }
}