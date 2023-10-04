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

namespace Thojou\LLMDocuments\Storage\VectorStore;

use Thojou\LLMDocuments\Document\DocumentInterface;
use Thojou\LLMDocuments\Storage\StorageInterface;

interface VectorStoreInterface
{
    /**
     * @param array<int, DocumentInterface> $documents
     *
     * @return void
     */
    public function addDocuments(array $documents): void;

    /**
     * @param array<int, string> $ids
     *
     * @return void
     */
    public function delete(array $ids): void;

    /**
     * @param string               $query
     * @param float                $similarityThreshold
     * @param int                  $limit
     * @param array<string, mixed> $filter
     *
     * @return array<int, DocumentInterface>
     */
    public function search(string $query, float $similarityThreshold = 0.75, int $limit = 4, array $filter = []): array;
}
