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

namespace Thojou\LLMDocuments\Tests\Search\Google;

use Google\Client;
use Google\Service\CustomSearchAPI;
use PHPUnit\Framework\TestCase;
use Thojou\LLMDocuments\Search\Google\GoogleSearchEngine;
use Thojou\LLMDocuments\Search\Google\GoogleSearchEngineFactory;
use Thojou\LLMDocuments\Search\SearchEngineInterface;
use Thojou\LLMDocuments\Search\SearchResultInterface;

class GoogleSearchEngineTest extends TestCase
{
    public function testSearch(): void
    {
        $cse = $this->createMock(CustomSearchAPI\Resource\Cse::class);
        $api = $this->createMock(CustomSearchAPI::class);
        $search = $this->createMock(CustomSearchAPI\Search::class);
        $searchResultItem = $this->createMock(CustomSearchAPI\Result::class);
        $search
            ->method('getItems')
            ->willReturn([$searchResultItem]);
        $cse
            ->method('listCse')
            ->willReturn($search);

        $searchResultItem
            ->method('getLink')
            ->willReturn('https://www.example.com');

        $api->cse = $cse;

        $engine = new GoogleSearchEngine($api, "testId");
        $result = $engine->search('Test', 1);

        $this->assertCount(1, $result);
        $this->assertInstanceOf(SearchResultInterface::class, $result[0]);
    }

}
