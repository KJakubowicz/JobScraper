<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\JobOffer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\ProcessLog;

class JobOfferControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexDisplaysJobOffersWithFilters()
    {
        $processLog = ProcessLog::create(['status' => 'completed']);
        $jobOffer = JobOffer::create(['url' => 'https://example.com']);
        $jobOffer->versions()->create([
            'job_title' => 'Software Engineer',
            'description' => 'Develop and maintain software applications.',
            'work_mode' => 'remote',
            'location' => 'New York',
            'work_type' => 'full-time',
            'process_log_id' => $processLog->id,
            'is_active' => true,
        ]);

        $response = $this->get(route('job-offers.index', [
            'job_title' => 'Software Engineer',
            'work_mode' => 'remote',
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('jobOffers', function ($jobOffers) use ($jobOffer) {
            return count($jobOffers) === 1 && $jobOffers[0]['jobOffer']->id === $jobOffer->id;
        });
        $response->assertViewHas('filters', function ($filters) {
            return $filters['job_title'] === 'Software Engineer' &&
                   $filters['work_mode'] === 'remote';
        });
    }

    public function testHistoryDisplaysVersionList()
    {
        $processLog = ProcessLog::create(['status' => 'completed']);
        $jobOffer = JobOffer::create(['url' => 'https://example.com']);
        $version1 = $jobOffer->versions()->create([
            'job_title' => 'Version 1',
            'description' => 'Description 1',
            'work_mode' => 'remote',
            'location' => 'New York',
            'work_type' => 'full-time',
            'process_log_id' => $processLog->id,
        ]);
        $version2 = $jobOffer->versions()->create([
            'job_title' => 'Version 2',
            'description' => 'Description 2',
            'work_mode' => 'office',
            'location' => 'Los Angeles',
            'work_type' => 'part-time',
            'process_log_id' => $processLog->id,
        ]);

       $response = $this->get(route('job-offers.history', ['jobOfferId' => $jobOffer->id]));

       $response->assertStatus(200);
       $response->assertViewIs('jobOffers.history');
       $response->assertViewHas('versionList', function ($versionList) use ($version1, $version2) {
           return $versionList->contains($version1) && $versionList->contains($version2);
       });
    }
}
