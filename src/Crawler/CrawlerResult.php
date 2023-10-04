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

namespace Thojou\LLMDocuments\Crawler;

class CrawlerResult implements CrawlerResultInterface
{
    public function __construct(
        private readonly string $content,
        private readonly ?string $contentType
    ) {
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getContentType(): string
    {
        return $this->contentType ?? 'text/html';
    }
}
