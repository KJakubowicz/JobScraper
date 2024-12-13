<?php

namespace App\Services\Scraper\Scrapers;

use Symfony\Component\Panther\Client;
use App\Services\Scraper\AbstractScraper;
use App\Models\JobOffer;
use App\Models\ProcessLog;
use Illuminate\Support\Facades\DB;

class WorkableScraper extends AbstractScraper
{
    private Client $client;

    public function __construct(Client $client, string $url)
    {
        $this->client = $client;
        $this->url = $url;
    }

    public function scrape(): array
    {
        $this->client->request('GET', $this->url);
        $this->clearFiltersIfNeeded();
        $this->selectLocation();
        $this->showMoreJobs();

        return $this->scrapeJobData($this->client);
    }

    public function updateDatabase(array $jobList, ProcessLog $processLog): int
    {
        $updatedRowsCount = 0;
        DB::beginTransaction();

        try {
            $existingJobs = JobOffer::whereIn('url', array_column($jobList, 'url'))->get();

            foreach ($jobList as $job) {
                $existingJob = $existingJobs->firstWhere('url', $job['url']);
                if ($existingJob) {
                    $existingJob->versions()->create([
                        'job_title' => $job['title'],
                        'description' => $job['department'],
                        'work_mode' => $job['workplace'],
                        'location' => $job['location'],
                        'work_type' => $job['type'],
                        'process_log_id' => $processLog->id,
                    ]);
                    $updatedRowsCount++;
                } else {
                    $newJob = JobOffer::create([
                        'url' => $job['url'],
                    ]);

                    $newJob->versions()->create([
                        'job_title' => $job['title'],
                        'description' => $job['department'],
                        'work_mode' => $job['workplace'],
                        'location' => $job['location'],
                        'work_type' => $job['type'],
                        'process_log_id' => $processLog->id,
                        'is_active' => true,
                    ]);
                    $updatedRowsCount++;
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $updatedRowsCount;
    }

    /**
     * Function to clear filters if needed
     * 
     * @return void
     */
    private function clearFiltersIfNeeded(): void
    {
        $this->client->waitForVisibility('[data-ui="clear-filters"]');
    
        $isFilterSelected = $this->client->getCrawler()->filter('[data-ui="pill-location-0"]')->count();
        if ($isFilterSelected) {
            $this->client->executeScript(
                'arguments[0].click();',
                [$this->client->getCrawler()->filter('[data-ui="clear-filters"]')->getElement(0)]
            );
        }
    }

    /**
     * Function to clear filters if needed
     * 
     * @return void
     */
    private function showMoreJobs(): void
    {
        $this->client->waitForVisibility('[data-ui="job"]');
        $clickCount = 0;
        while (true) {
            $isShowMoreButtonVisible = $this->client->getCrawler()->filter('[data-ui="load-more-button"]')->count();
            if ($isShowMoreButtonVisible === 0 || $clickCount === 10) {
                break;
            }

            $this->client->executeScript (
                'arguments[0].click();',
                [$this->client->getCrawler()->filter('[data-ui="load-more-button"]')->getElement(0)]
            );
            sleep(1);
            $clickCount++;
        }
    }

    /**
     * Function to select location
     * 
     * @return void
     */
    private function selectLocation(): void
    {
        $this->client->waitForVisibility('#locations-filter_input');
        $this->client->executeScript(
            'arguments[0].click();',
            [$this->client->getCrawler()->filter('#locations-filter_input')->getElement(0)]
        );
    
        $this->client->waitForVisibility('#locations-filter_listbox');
        $this->client->executeScript(
            'arguments[0].click();',
            [$this->client->getCrawler()->filter('[value="Poland, Masovian Voivodeship, Warsaw"]')->getElement(0)]
        );
    }
    
    /**
     * Function to scrape job data
     * 
     * @return array
     */
    private function scrapeJobData(): array
    {
        $scrapedData = [];
        $this->client->waitForVisibility('[data-ui="job"]');
        sleep(1);
        $crawler = $this->client->getCrawler();
        $elementsCount = 1;

        $crawler->filter('[data-ui="job"]')->each(function ($node) use (&$scrapedData, &$elementsCount) {
            echo "Element no." . $elementsCount . PHP_EOL;
            $scrapedData[] = [
                'title' => $node->filter('[data-ui="job-title"]')->text(),
                'url' => $this->buildJobOfferUrl($node->filter('a')->attr('href')),
                'workplace' => $node->filter('[data-ui="job-workplace"]')->text(),
                'location' => $node->filter('[data-ui="job-location"]')->text(),
                'department' => $node->filter('[data-ui="job-department"]')->text(),
                'type' => $node->filter('[data-ui="job-type"]')->text(),
            ];
            $elementsCount++;
        });
    
        $this->client->quit();
    
        return $scrapedData;
    }

    /**
     * Function to build job offer URL
     * 
     * @param string $href
     * @return string
     */
    private function buildJobOfferUrl(string $href): string
    {
        $parsedUrl = parse_url($this->url);
        $baseUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];

        return $baseUrl . $href;
    }
}