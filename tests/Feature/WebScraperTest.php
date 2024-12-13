<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Services\Scraper\ScraperFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use App\Services\Scraper\ScraperInterface;
use Illuminate\Support\Facades\App;

class WebScraperTest extends TestCase
{
    public function testScraperCommandSuccessfullyProcessesData()
    {
        $scraperFactoryMock = Mockery::mock(ScraperFactory::class);
        $scraperMock = Mockery::mock(ScraperInterface::class);
        $scraperFactoryMock->shouldReceive('getScraperByProvider')
            ->once()
            ->with('workable')
            ->andReturn($scraperMock);
        $scraperMock->shouldReceive('scrape')
            ->once()
            ->andReturn(['data' => 'example_data']);
        $scraperMock->shouldReceive('updateDatabase')
            ->once()
            ->andReturn(10);

        Log::shouldReceive('error')->never();

        App::instance(ScraperFactory::class, $scraperFactoryMock);

        Artisan::call('app:web-scraper', ['provider' => 'workable']);
        $this->assertStringContainsString(
            'Scraped data from workable and updated 10 rows in the database.',
            trim(Artisan::output())
        );

         $this->assertDatabaseHas('process_logs', [
            'status' => 'completed'
        ]);
    }

    public function testScraperCommandHandlesError()
    {
        $scraperFactoryMock = Mockery::mock(ScraperFactory::class);
        $scraperMock = Mockery::mock(ScraperInterface::class);
        $scraperFactoryMock->shouldReceive('getScraperByProvider')
            ->once()
            ->with('workable')
            ->andReturn($scraperMock);
        $scraperMock->shouldReceive('scrape')
            ->once()
            ->andThrow(new \Exception('Scraping failed'));

        Log::shouldReceive('error')
            ->once()
            ->with('Error occurred while scraping data from workable: Scraping failed');

        App::instance(ScraperFactory::class, $scraperFactoryMock);

        Artisan::call('app:web-scraper', ['provider' => 'workable']);

        $this->assertStringContainsString(
            'Failed to scrape data from workable. Error: Scraping failed',
            Artisan::output()
        );

        $this->assertDatabaseHas('process_logs', [
            'status' => 'failed'
        ]);
    }
}