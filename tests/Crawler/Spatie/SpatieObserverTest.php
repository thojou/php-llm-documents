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

use GuzzleHttp\Exception\RequestException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Thojou\LLMDocuments\Crawler\CrawlerInterface;
use Thojou\LLMDocuments\Crawler\CrawlerResultInterface;
use Thojou\LLMDocuments\Crawler\Spatie\SpatieCrawlerConfig;
use Thojou\LLMDocuments\Crawler\Spatie\SpatieCrawlerFactory;
use Thojou\LLMDocuments\Crawler\Spatie\SpatieObserver;

class SpatieObserverTest extends TestCase
{
    public function testCrawled(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $stream
            ->expects($this->once())
            ->method('getContents')
            ->willReturn("Any content");

        $observer = new SpatieObserver();

        $observer->crawled($uri, $response);

        $this->assertInstanceOf(CrawlerResultInterface::class, $observer->getResult());
        $this->assertEquals("Any content", $observer->getResult()->getContent());
    }

    public function testCrawlFailed(): void
    {
        $this->expectException(RequestException::class);

        $uri = $this->createMock(UriInterface::class);
        $requestException = $this->createMock(RequestException::class);

        $observer = new SpatieObserver();
        $observer->crawlFailed($uri, $requestException);
    }
}
