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

namespace Thojou\LLMDocuments\Search;

class SearchResult implements SearchResultInterface
{
    /**
     * @param string               $source
     * @param int                  $index
     * @param array<string, mixed> $metadata
     */
    public function __construct(
        private readonly string $source,
        private readonly int $index,
        private readonly array $metadata
    ) {
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function getIndex(): int
    {
        return $this->index;
    }
}
