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

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Thojou\LLMDocuments\Crawler\CrawlerFactoryInterface;
use Thojou\LLMDocuments\Crawler\CrawlerInterface;
use Thojou\LLMDocuments\Crawler\CrawlerResultInterface;
use Thojou\LLMDocuments\Document\DocumentInterface;
use Thojou\LLMDocuments\Loader\WebSearchLoader;
use Thojou\LLMDocuments\Parser\ParserFactoryInterface;
use Thojou\LLMDocuments\Parser\ParserInterface;
use Thojou\LLMDocuments\Search\SearchEngineFactoryInterface;
use Thojou\LLMDocuments\Search\SearchEngineInterface;
use Thojou\LLMDocuments\Search\SearchResultInterface;

class WebSearchLoaderTest extends TestCase
{
    public function testLoad(): void
    {
        $crawlerFactory = $this->createCrawlerMock();
        $searchEngineFactory = $this->createSearchEngineMock();
        $parserFactory = $this->createParserMock();

        $loader = new WebSearchLoader($searchEngineFactory, $crawlerFactory, $parserFactory);
        $documents = $loader->load('What is LLM');

        $document = $documents[0];
        $this->assertInstanceOf(DocumentInterface::class, $document);
        $this->assertEquals('Test content', $document->getPageContent());
        $this->assertEquals('https://www.example.com', $document->getMetadata()['source']);
        $this->assertEquals('text/html', $document->getMetadata()['mimeType']);
    }

    /**
     * @return CrawlerFactoryInterface&MockObject
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function createCrawlerMock(): CrawlerFactoryInterface|MockObject
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
            ->expects($this->atLeastOnce())
            ->method('getContent')
            ->willReturn('Test content');
        $crawlerResult
            ->expects($this->atLeastOnce())
            ->method('getContentType')
            ->willReturn('text/html');
        return $crawlerFactory;
    }

    /**
     * @return SearchEngineFactoryInterface&MockObject
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function createSearchEngineMock(): SearchEngineFactoryInterface|MockObject
    {
        $searchEngineFactory = $this->createMock(SearchEngineFactoryInterface::class);
        $searchEngine = $this->createMock(SearchEngineInterface::class);
        $searchResult = $this->createMock(SearchResultInterface::class);

        $searchEngineFactory
            ->expects($this->once())
            ->method('create')
            ->willReturn($searchEngine);

        $searchEngine
            ->expects($this->once())
            ->method('search')
            ->with($this->equalTo('What is LLM'))
            ->willReturn([$searchResult]);

        $searchResult
            ->expects($this->atLeastOnce())
            ->method('getSource')
            ->willReturn('https://www.example.com');
        return $searchEngineFactory;
    }

    /**
     * @return ParserFactoryInterface&MockObject
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function createParserMock(): ParserFactoryInterface|MockObject
    {
        $parserFactory = $this->createMock(ParserFactoryInterface::class);
        $parser = $this->createMock(ParserInterface::class);

        $parserFactory
            ->expects($this->once())
            ->method('create')
            ->willReturn($parser);

        $parser
            ->expects($this->once())
            ->method('parse')
            ->willReturn('Test content');
        return $parserFactory;
    }


}
