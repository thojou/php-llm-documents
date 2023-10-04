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

namespace Thojou\LLMDocuments\Tests\Search;

use PHPUnit\Framework\TestCase;
use Thojou\LLMDocuments\Search\SearchResult;
use Thojou\LLMDocuments\Search\SearchResultInterface;

class SearchResultTest extends TestCase
{
    public function testSearchResult(): void
    {
        $result = new SearchResult(
            'https://www.example.com',
            1,
            [
                'title' => 'Example',
            ]
        );

        $this->assertInstanceOf(SearchResultInterface::class, $result);
        $this->assertEquals('https://www.example.com', $result->getSource());
        $this->assertEquals(1, $result->getIndex());
        $this->assertEquals('Example', $result->getMetadata()['title']);
    }

}
