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

namespace Thojou\LLMDocuments\Search\Google;

use Google\Client;
use Google\Service\CustomSearchAPI;
use Thojou\LLMDocuments\Search\SearchEngineFactoryInterface;
use Thojou\LLMDocuments\Search\SearchEngineInterface;

class GoogleSearchEngineFactory implements SearchEngineFactoryInterface
{
    public function __construct(
        private readonly Client $client,
        private readonly string $searchEngineId
    ) {
    }

    public function create(): SearchEngineInterface
    {
        return new GoogleSearchEngine(
            new CustomSearchAPI($this->client),
            $this->searchEngineId
        );
    }
}
