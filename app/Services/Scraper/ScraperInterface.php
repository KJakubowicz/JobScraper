<?php

namespace App\Services\Scraper;

use App\Models\ProcessLog;

interface ScraperInterface
{
    /**
     * Scrape data from the given URL
     *
     * @return array
     */
    public function scrape(): array;

    /**
     * Update database with scraped data
     *
     * @param array $data
     * @param ProcessLog $processLog
     * @return int
     */
    public function updateDatabase(array $data, ProcessLog $processLog): int;
}