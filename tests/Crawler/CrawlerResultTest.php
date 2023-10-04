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

namespace Thojou\LLMDocuments\Tests\Crawler;

use PHPUnit\Framework\TestCase;
use Thojou\LLMDocuments\Crawler\CrawlerResult;

class CrawlerResultTest extends TestCase
{
    public function testCrawlerResult(): void
    {
        $result = new CrawlerResult("TestContent", "text/plain");

        $this->assertEquals("TestContent", $result->getContent());
        $this->assertEquals("text/plain", $result->getContentType());
    }

}
