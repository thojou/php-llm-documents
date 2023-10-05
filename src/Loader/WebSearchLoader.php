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

namespace Thojou\LLMDocuments\Loader;

use Exception;
use Thojou\LLMDocuments\Chain\Chains\MapChain;
use Thojou\LLMDocuments\Chain\Chains\Runnable;
use Thojou\LLMDocuments\Chain\Chains\SequentialChain;
use Thojou\LLMDocuments\Crawler\CrawlerFactoryInterface;
use Thojou\LLMDocuments\Document\Document;
use Thojou\LLMDocuments\Document\DocumentInterface;
use Thojou\LLMDocuments\Parser\ParserFactoryInterface;
use Thojou\LLMDocuments\Search\SearchEngineFactoryInterface;
use Thojou\LLMDocuments\Search\SearchResultInterface;
use Thojou\LLMDocuments\Utils\TempFile;

class WebSearchLoader implements LoaderInterface
{
    public function __construct(
        private readonly SearchEngineFactoryInterface $searchEngineFactory,
        private readonly CrawlerFactoryInterface $crawlerFactory,
        private readonly ParserFactoryInterface $parserFactory,
        private readonly int $limit = 10
    ) {
    }


    /**
     * @param string $resource
     *
     * @return array<int, DocumentInterface>
     */
    public function load(string $resource): array
    {
        $searchEngine = $this->searchEngineFactory->create();

        echo "Searching for \"$resource\"...\n";

        $results = $searchEngine->search($resource, $this->limit);

        echo "Found " . count($results) . " results.\n";

        return array_map(
            $this->handleSearchResult(...),
            $results
        );
    }

    /**
     * @throws Exception
     */
    private function handleSearchResult(SearchResultInterface $searchResult): DocumentInterface
    {
        echo "Crawling \"" . $searchResult->getSource() . "\"...\n";

        $crawlerResult = $this->crawlerFactory
            ->create()
            ->crawl($searchResult->getSource());

        echo "Crawled \"" . $searchResult->getSource() . "\".\n";

        echo "Parsing \"" . $searchResult->getSource() . "\"...\n";

        $pageContent = $this->parserFactory
            ->create($crawlerResult->getContentType())
            ->parse(TempFile::fromContent(
                $crawlerResult->getContent(),
                $crawlerResult->getContentType()
            ));

        echo "Parsed \"" . $searchResult->getSource() . "\".\n";

        return new Document(
            $pageContent,
            array_merge($searchResult->getMetadata(), [
                'source' => $searchResult->getSource(),
                'searchIndex' => $searchResult->getIndex(),
                'mimeType' => $crawlerResult->getContentType()
            ])
        );
    }
}
