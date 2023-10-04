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
use PHPUnit\Framework\TestCase;
use Thojou\LLMDocuments\Search\Google\GoogleSearchEngineFactory;
use Thojou\LLMDocuments\Search\SearchEngineInterface;

class GoogleSearchEngineFactoryTest extends TestCase
{
    public function testFactory(): void
    {
        $client = $this->createMock(Client::class);
        $factory = new GoogleSearchEngineFactory($client, 'testId');
        $engine = $factory->create();

        $this->assertInstanceOf(SearchEngineInterface::class, $engine);
    }

}
