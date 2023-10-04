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

use Spatie\Browsershot\Browsershot;
use Spatie\Crawler\Crawler;
use Thojou\LLMDocuments\Crawler\CrawlerFactoryInterface;
use Thojou\LLMDocuments\Crawler\CrawlerInterface;

class SpatieCrawlerFactory implements CrawlerFactoryInterface
{
    public function __construct(
        private readonly SpatieCrawlerConfig $config
    ) {
    }

    public function create(): CrawlerInterface
    {
        $observer = new SpatieObserver();

        $crawler = Crawler::create()
            ->setUserAgent($this->config->getUserAgent())
            ->setMaximumDepth(0)
            ->setCrawlObserver($observer);


        if($this->config->isEnableJavascript()) {
            $browserShot = $this->setupBrowserShot();
            $crawler
                ->setBrowsershot($browserShot)
                ->executeJavaScript();
        }

        return new SpatieCrawlerAdapter($crawler, $observer);
    }

    private function setupBrowserShot(): Browsershot
    {
        $browserShot = new Browsershot();

        if ($this->config->getBrowserIp() && $this->config->getBrowserPort()) {
            $browserShot->setRemoteInstance($this->config->getBrowserIp(), $this->config->getBrowserPort());
        }

        if ($this->config->getNodeBinary()) {
            $browserShot->setNodeBinary($this->config->getNodeBinary());
        }

        if ($this->config->getNpmBinary()) {
            $browserShot->setNpmBinary($this->config->getNpmBinary());
        }

        return $browserShot;
    }
}
