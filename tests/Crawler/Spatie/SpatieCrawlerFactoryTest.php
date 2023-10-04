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

namespace Thojou\LLMDocuments\Tests\Crawler\Spatie;

use PHPUnit\Framework\TestCase;
use Thojou\LLMDocuments\Crawler\CrawlerInterface;
use Thojou\LLMDocuments\Crawler\Spatie\SpatieCrawlerConfig;
use Thojou\LLMDocuments\Crawler\Spatie\SpatieCrawlerFactory;

class SpatieCrawlerFactoryTest extends TestCase
{
    public function testSpatieCrawlerFactory(): void
    {
        $config = new SpatieCrawlerConfig('LLMDocuments');
        $config
            ->setNodeBinary('/usr/bin/node')
            ->setNpmBinary('/usr/bin/npm')
            ->setEnableJavascript(true)
            ->setRemoteInstance('127.0.0.1', 9222);

        $factory = new SpatieCrawlerFactory($config);
        $crawler = $factory->create();

        $this->assertInstanceOf(CrawlerInterface::class, $crawler);
    }
}
