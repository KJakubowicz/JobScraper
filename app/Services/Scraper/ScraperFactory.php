<?php

namespace App\Services\Scraper;

use App\Services\Scraper\Scrapers\WorkableScraper;
use App\Services\Scraper\ScraperInterface;
use Symfony\Component\Panther\Client as PantherClient;

class ScraperFactory
{
    CONST PROVIDER_LIST = [
        'workable' => 'https://apply.workable.com/testronic'
    ];
    
    /**
     * Get a scraper by provider
     *
     * @param string $provider
     * @return ScraperInterface
     * @throws \InvalidArgumentException
     */
    public function getScraperByProvider(string $provider): ScraperInterface
    {
        if (!array_key_exists($provider, self::PROVIDER_LIST)) {
            throw new \InvalidArgumentException('Invalid provider');
        }

        return match ($provider) {
            'workable' => new WorkableScraper(
                PantherClient::createChromeClient(
                    null,
                    [
                        '--headless',
                        '--disable-gpu',
                        '--no-sandbox',
                        '--remote-debugging-port=9222',
                    ]
                ), self::PROVIDER_LIST[$provider]
            ),
            default => throw new \InvalidArgumentException('Provider not supported'),
        };
    }
}