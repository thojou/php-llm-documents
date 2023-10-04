<?php

declare(strict_types=1);

/*
 * This file is part of PHP LLM Documents.
 *
 * (c) Thomas Joußen <tjoussen91@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Thojou\LLMDocuments\Crawler\Spatie;

use Spatie\Crawler\Crawler;
use Thojou\LLMDocuments\Crawler\CrawlerInterface;
use Thojou\LLMDocuments\Crawler\CrawlerResultInterface;

class SpatieCrawlerAdapter implements CrawlerInterface
{
    public function __construct(
        private readonly Crawler $crawler,
        private readonly SpatieObserver $observer
    ) {
    }

    public function crawl(string $url): CrawlerResultInterface
    {
        $this->crawler
            ->startCrawling($url);

        return $this->observer->getResult();
    }
}
