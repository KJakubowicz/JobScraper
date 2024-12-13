<?php

namespace Tests\Feature;

use App\Models\ProcessLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProcessLogControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexDisplaysProcessLogs()
    {
        $processLog1 = ProcessLog::create([
            'status' => 'success',
            'records_processed' => 100,
        ]);
        $processLog2 = ProcessLog::create([
            'status' => 'failure',
            'records_processed' => 200,
        ]);
        $response = $this->get(route('process-logs.index'));
        $response->assertStatus(200);
        $response->assertViewIs('processLog.index');
        $response->assertViewHas('processLogList', function ($processLogList) use ($processLog1, $processLog2) {
            return $processLogList->contains($processLog1) && $processLogList->contains($processLog2);
        });
    }
}
