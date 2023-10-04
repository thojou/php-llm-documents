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

namespace Thojou\LLMDocuments\Crawler\Spatie;

use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlObservers\CrawlObserver;
use Symfony\Component\DomCrawler\Crawler;
use Thojou\LLMDocuments\Crawler\CrawlerResult;
use Thojou\LLMDocuments\Crawler\CrawlerResultInterface;

class SpatieObserver extends CrawlObserver
{
    private CrawlerResultInterface $result;

    public function crawled(
        UriInterface $url,
        ResponseInterface $response,
        UriInterface $foundOnUrl = null,
        string $linkText = null,
    ): void {
        $this->result = new CrawlerResult(
            $response->getBody()->getContents(),
            trim(explode(';', $response->getHeaderLine('Content-Type'))[0])
        );
    }

    public function crawlFailed(
        UriInterface $url,
        RequestException $requestException,
        UriInterface $foundOnUrl = null,
        string $linkText = null,
    ): void {
        throw $requestException;
    }

    public function getResult(): CrawlerResultInterface
    {
        return $this->result;
    }
}
