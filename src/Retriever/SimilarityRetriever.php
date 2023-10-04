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

namespace Thojou\LLMDocuments\Retriever;

use Thojou\LLMDocuments\Storage\VectorStore\VectorStoreInterface;

class SimilarityRetriever implements RetrieverInterface
{
    public function __construct(
        private readonly VectorStoreInterface $vectorStore,
        private readonly float $similarityThreshold = 0.75,
        private readonly int $limit = 4,
    ) {
    }

    public function getRelevantDocuments(string $query): array
    {
        return $this->vectorStore->search($query, $this->similarityThreshold, $this->limit);
    }

    public function addDocuments(array $documents): void
    {
        $this->vectorStore->addDocuments($documents);
    }
}
