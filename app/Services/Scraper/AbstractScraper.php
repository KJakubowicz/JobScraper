<?php

namespace App\Services\Scraper;

use App\Services\Scraper\ScraperInterface;
use App\Models\ProcessLog;

abstract class AbstractScraper implements ScraperInterface
{
    protected string $url;

    abstract public function scrape(): array;

    abstract public function updateDatabase(array $data, ProcessLog $processLog): int;
}