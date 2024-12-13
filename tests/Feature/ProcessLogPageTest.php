<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\ProcessLog;

class ProcessLogPageTest extends TestCase
{
    use RefreshDatabase;

    public function testProcessLogsPageLoadsCorrectly()
    {
        $processLog = ProcessLog::create(['status' => 'completed']);
        $response = $this->get(route('process-logs.index'));
        $response->assertStatus(200);
        $response->assertViewHas('processLogList');
    }
}
