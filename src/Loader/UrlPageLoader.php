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

use Thojou\LLMDocuments\Crawler\CrawlerFactoryInterface;
use Thojou\LLMDocuments\Document\Document;
use Thojou\LLMDocuments\Document\DocumentInterface;

class UrlPageLoader implements LoaderInterface
{
    public function __construct(
        private readonly CrawlerFactoryInterface $crawlerFactory
    ) {
    }

    /**
     * @param string $resource
     *
     * @return DocumentInterface
     * @throws \Exception
     */
    public function load(string $resource): DocumentInterface
    {
        if(filter_var($resource, FILTER_VALIDATE_URL) === false) {
            throw new \Exception("Invalid resource given: '$resource'");
        }

        $crawler = $this->crawlerFactory->create();
        $result = $crawler->crawl($resource);

        return new Document(
            $result->getContent(),
            [
                'source' => $resource,
                'mimeType' => $result->getContentType(),
            ]
        );
    }
}
