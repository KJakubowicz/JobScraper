<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Scraper\ScraperFactory;
use App\Models\ProcessLog;
use Illuminate\Support\Facades\Log;

class WebScraper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:web-scraper {provider}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape data from a website';

    /**
     * Factory for creating scrapers
     *
     * @var ScraperFactory
     */
    private $scraperFactory;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ScraperFactory $scraperFactory)
    {
        parent::__construct();

        $this->scraperFactory = $scraperFactory;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $provider = mb_strtolower($this->argument('provider'));
        $processLog = ProcessLog::create(['status' => 'pending']);
        try {
            $scraper = $this->scraperFactory->getScraperByProvider($provider);
            $scrapedData = $scraper->scrape();
            $updatedRowsCount = $scraper->updateDatabase($scrapedData, $processLog);

            $processLog->records_processed = $updatedRowsCount;
            $processLog->status = 'completed';
            $processLog->save();

            $this->info("Scraped data from {$provider} and updated {$updatedRowsCount} rows in the database.");
        } catch (\Exception $e) {
            if (isset($processLog)) {
                $processLog->status = 'failed';
                $processLog->save();
            }

            Log::error("Error occurred while scraping data from {$provider}: " . $e->getMessage());
            $this->error("Failed to scrape data from {$provider}. Error: " . $e->getMessage());
        } catch (\InvalidArgumentException $e) {
            if (isset($processLog)) {
                $processLog->status = 'failed';
                $processLog->save();
            }

            Log::error("Error occurred while scraping data from {$provider}: " . $e->getMessage());
            $this->error("Failed to scrape data from {$provider}. Error: " . $e->getMessage());
        }
    }
}
