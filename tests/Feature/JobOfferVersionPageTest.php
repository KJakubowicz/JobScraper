<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\JobOffer;
use App\Models\ProcessLog;

class JobOfferVersionPageTest extends TestCase
{
    use RefreshDatabase;

    public function testSetActiveVersion()
    {
        $jobOffer = JobOffer::create(['url' => 'https://example-version.com']);
        $processLog = ProcessLog::create(['status' => 'completed']);
        $version1 = $jobOffer->versions()->create([
            'job_title' => 'Software Engineer',
            'description' => 'Develop software.',
            'work_mode' => 'remote',
            'location' => 'New York',
            'work_type' => 'full-time',
            'is_active' => false,
            'process_log_id' => $processLog->id,
        ]);
        $version2 = $jobOffer->versions()->create([
            'job_title' => 'Senior Software Engineer',
            'description' => 'Develop and maintain software.',
            'work_mode' => 'remote',
            'location' => 'New York',
            'work_type' => 'full-time',
            'is_active' => true,
            'process_log_id' => $processLog->id,
        ]);

        $response = $this->post(route('job-offers-version.set-active-version', ['versionId' => $version1->id]));
        $response->assertRedirect();
        $response->assertSessionHas('status', 'Active version updated successfully.');

        $this->assertDatabaseHas('job_offer_versions', [
            'id' => $version1->id,
            'is_active' => true,
        ]);
    }

    public function testDeleteVersion()
    {
        $jobOffer = JobOffer::create(['url' => 'https://example-version.com']);
        $processLog = ProcessLog::create(['status' => 'completed']);
        $version = $jobOffer->versions()->create([
            'job_title' => 'Software Engineer',
            'description' => 'Develop software.',
            'work_mode' => 'remote',
            'location' => 'New York',
            'work_type' => 'full-time',
            'is_active' => false,
            'process_log_id' => $processLog->id,
        ]);

        $response = $this->delete(route('job-offers-version.delete-version', ['versionId' => $version->id]));
        $response->assertRedirect();
        $response->assertSessionHas('status', 'Version deleted successfully.');

        $this->assertDatabaseMissing('job_offer_versions', [
            'id' => $version->id,
        ]);
    }
}
