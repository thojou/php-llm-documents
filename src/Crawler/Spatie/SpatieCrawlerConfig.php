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

class SpatieCrawlerConfig
{
    private bool $enableJavascript = false;
    private ?string $browserIp = null;
    private ?int $browserPort = null;
    private ?string $nodeBinary = null;
    private ?string $npmBinary = null;

    public function __construct(
        private readonly string $userAgent = '',
    ) {
    }

    public function isEnableJavascript(): bool
    {
        return $this->enableJavascript;
    }

    public function setEnableJavascript(bool $enableJavascript): SpatieCrawlerConfig
    {
        $this->enableJavascript = $enableJavascript;
        return $this;
    }

    public function setRemoteInstance(string $browserIp, int $browserPort = 9222): SpatieCrawlerConfig
    {
        $this->browserIp = $browserIp;
        $this->browserPort = $browserPort;

        return $this;
    }

    public function getBrowserIp(): ?string
    {
        return $this->browserIp;
    }

    public function getBrowserPort(): ?int
    {
        return $this->browserPort;
    }

    public function getNodeBinary(): ?string
    {
        return $this->nodeBinary;
    }

    public function setNodeBinary(string $nodeBinary): SpatieCrawlerConfig
    {
        $this->nodeBinary = $nodeBinary;
        return $this;
    }

    public function getNpmBinary(): ?string
    {
        return $this->npmBinary;
    }

    public function setNpmBinary(string $npmBinary): SpatieCrawlerConfig
    {
        $this->npmBinary = $npmBinary;
        return $this;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }
}
