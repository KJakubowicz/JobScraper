<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\JobOffer;
use App\Models\ProcessLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JobOfferPageTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexPageLoadsCorrectly(): void
    {
        $response = $this->get(route('job-offers.index'));
        $response->assertStatus(200);
        $response->assertViewHas('jobOffers');
    }

    public function testHistoryPageLoadsCorrectly(): void
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
        $response = $this->get(route('job-offers.history', ['jobOfferId' => $jobOffer->id]));
        $response->assertStatus(200);
        $response->assertViewHas('versionList');
    }
}
