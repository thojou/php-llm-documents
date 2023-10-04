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
use Thojou\LLMDocuments\Crawler\Spatie\SpatieCrawlerConfig;

class SpatieCrawlerConfigTest extends TestCase
{
    public function testSpatieCrawlerConfig(): void
    {
        $config = new SpatieCrawlerConfig('LLMDocuments');

        $this->assertEquals('LLMDocuments', $config->getUserAgent());
        $this->assertFalse($config->isEnableJavascript());
        $this->assertNull($config->getBrowserIp());
        $this->assertNull($config->getBrowserPort());
        $this->assertNull($config->getNodeBinary());
        $this->assertNull($config->getNpmBinary());


        $config
            ->setNodeBinary('/usr/bin/node')
            ->setNpmBinary('/usr/bin/npm')
            ->setEnableJavascript(true)
            ->setRemoteInstance('127.0.0.1', 9222);

        $this->assertTrue($config->isEnableJavascript());
        $this->assertEquals('/usr/bin/node', $config->getNodeBinary());
        $this->assertEquals('/usr/bin/npm', $config->getNpmBinary());
        $this->assertEquals('127.0.0.1', $config->getBrowserIp());
        $this->assertEquals('9222', $config->getBrowserPort());

    }

}
