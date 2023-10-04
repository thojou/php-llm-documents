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

namespace Thojou\LLMDocuments\Tests\Crawler\Spatie;

use PHPUnit\Framework\TestCase;
use Spatie\Crawler\Crawler;
use Thojou\LLMDocuments\Crawler\CrawlerResultInterface;
use Thojou\LLMDocuments\Crawler\Spatie\SpatieCrawlerAdapter;
use Thojou\LLMDocuments\Crawler\Spatie\SpatieObserver;

class SpatieCrawlerAdapterTest extends TestCase
{
    public function testSpatieAdapter(): void
    {
        $crawler = $this->createMock(Crawler::class);
        $observer = $this->createMock(SpatieObserver::class);
        $result = $this->createMock(CrawlerResultInterface::class);

        $crawler
            ->expects($this->once())
            ->method('startCrawling')
            ->with($this->equalTo('https://example.com'));


        $observer
            ->expects($this->once())
            ->method('getResult')
            ->willReturn($result);


        $adapter = new SpatieCrawlerAdapter($crawler, $observer);

        $this->assertSame($result, $adapter->crawl('https://example.com'));
    }

}
