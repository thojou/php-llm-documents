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
use Thojou\LLMDocuments\Search\SearchEngineInterface;
use Thojou\LLMDocuments\Search\SearchResult;

class GoogleSearchEngine implements SearchEngineInterface
{
    public function __construct(
        private readonly CustomSearchAPI $googleSearchApi,
        private readonly string $searchEngineId,
        private readonly bool $restricted = false
    ) {
    }

    public function search(string $query, int $limit): array
    {
        $request = [
            'cx' => $this->searchEngineId,
            'num' => $limit,
            'q' => $query,
        ];

        $result = match ($this->restricted) {
            true => $this->googleSearchApi->cse_siterestrict->listCseSiterestrict($request),
            default => $this->googleSearchApi->cse->listCse($request),
        };

        return array_map(
            fn (CustomSearchAPI\Result $item, int|string $index) => new SearchResult(
                $item->getLink(),
                (int)$index,
                [
                    'url' => $item->getLink(),
                    'title' => $item->getTitle(),
                    'snippet' => $item->getSnippet(),
                    'mimeType' => $item->getMime(),
                    'cacheId' => $item->getCacheId(),
                ]
            ),
            $result->getItems(),
            array_keys($result->getItems())
        );
    }
}
