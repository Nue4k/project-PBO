<?php

namespace App\Repositories;

use App\Models\Job;
use App\Models\CompanyProfile;
use Illuminate\Support\Facades\DB;

class JobRepository implements JobRepositoryInterface
{
    public function getAllActiveJobs()
    {
        $now = now();
        return Job::where('is_active', true)
            ->where(function($query) use ($now) {
                $query->whereNull('closing_date')
                     ->orWhere('closing_date', '>', $now);
            })
            ->with('companyProfile')
            ->get();
    }

    public function getJobsByCompanyId(string $companyId)
    {
        return Job::where('company_id', $companyId)
            ->withCount('applications')
            ->get();
    }

    public function findById(string $id)
    {
        return Job::with('companyProfile')->find($id);
    }

    public function create(array $data)
    {
        return Job::create($data);
    }

    public function update(string $id, array $data)
    {
        $job = Job::find($id);
        if ($job) {
            $job->update($data);
        }
        return $job;
    }

    public function delete(string $id)
    {
        $job = Job::find($id);
        if ($job) {
            return $job->delete();
        }
        return false;
    }

    public function closeJob(string $id)
    {
        $job = Job::find($id);
        if ($job) {
            $job->update(['is_active' => false]);
            return $job;
        }
        return null;
    }
}