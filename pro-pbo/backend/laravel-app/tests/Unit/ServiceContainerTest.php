<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\JobServiceInterface;
use App\Services\ApplicationServiceInterface;
use App\Services\AuthServiceInterface;
use App\Services\ProfileServiceInterface;
use App\Repositories\JobRepositoryInterface;
use App\Repositories\ApplicationRepositoryInterface;

class ServiceContainerTest extends TestCase
{
    public function test_job_service_is_injected()
    {
        $jobService = app(JobServiceInterface::class);
        $this->assertNotNull($jobService);
    }

    public function test_application_service_is_injected()
    {
        $applicationService = app(ApplicationServiceInterface::class);
        $this->assertNotNull($applicationService);
    }

    public function test_auth_service_is_injected()
    {
        $authService = app(AuthServiceInterface::class);
        $this->assertNotNull($authService);
    }

    public function test_profile_service_is_injected()
    {
        $profileService = app(ProfileServiceInterface::class);
        $this->assertNotNull($profileService);
    }

    public function test_job_repository_is_injected()
    {
        $jobRepository = app(JobRepositoryInterface::class);
        $this->assertNotNull($jobRepository);
    }

    public function test_application_repository_is_injected()
    {
        $applicationRepository = app(ApplicationRepositoryInterface::class);
        $this->assertNotNull($applicationRepository);
    }
}