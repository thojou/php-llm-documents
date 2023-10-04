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

namespace Thojou\LLMDocuments\Tests\Loader;

use PHPUnit\Framework\TestCase;
use Thojou\LLMDocuments\Crawler\CrawlerFactoryInterface;
use Thojou\LLMDocuments\Crawler\CrawlerInterface;
use Thojou\LLMDocuments\Crawler\CrawlerResultInterface;
use Thojou\LLMDocuments\Document\DocumentInterface;
use Thojou\LLMDocuments\Loader\UrlPageLoader;

class UrlPageLoaderTest extends TestCase
{
    public function testLoadInvalidResource(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid resource given: 'invalid'");

        $crawlerFactory = $this->createMock(CrawlerFactoryInterface::class);

        $loader = new UrlPageLoader($crawlerFactory);
        $loader->load('invalid');
    }

    public function testLoad(): void
    {
        $crawlerFactory = $this->createMock(CrawlerFactoryInterface::class);
        $crawler = $this->createMock(CrawlerInterface::class);
        $crawlerResult = $this->createMock(CrawlerResultInterface::class);

        $crawlerFactory
            ->expects($this->once())
            ->method('create')
            ->willReturn($crawler);

        $crawler
            ->expects($this->once())
            ->method('crawl')
            ->with($this->equalTo('https://www.example.com'))
            ->willReturn($crawlerResult);

        $crawlerResult
            ->expects($this->once())
            ->method('getContent')
            ->willReturn('Test content');
        $crawlerResult
            ->expects($this->once())
            ->method('getContentType')
            ->willReturn('text/html');

        $loader = new UrlPageLoader($crawlerFactory);
        $document = $loader->load('https://www.example.com');

        $this->assertInstanceOf(DocumentInterface::class, $document);
        $this->assertEquals('Test content', $document->getPageContent());
        $this->assertEquals('https://www.example.com', $document->getMetadata()['source']);
        $this->assertEquals('text/html', $document->getMetadata()['mimeType']);
    }

}
