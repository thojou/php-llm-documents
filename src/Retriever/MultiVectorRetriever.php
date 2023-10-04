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

use Thojou\LLMDocuments\Document\DocumentInterface;
use Thojou\LLMDocuments\Storage\DocumentStore\DocumentStoreInterface;
use Thojou\LLMDocuments\Storage\VectorStore\VectorStoreInterface;

abstract class MultiVectorRetriever implements RetrieverInterface
{
    public function __construct(
        protected readonly VectorStoreInterface $vectorStore,
        protected readonly DocumentStoreInterface $documentStore,
    ) {
    }

    /**
     * @param string $query
     *
     * @return array<int, DocumentInterface>
     */
    public function getRelevantDocuments(string $query): array
    {
        $childDocuments = $this->vectorStore->search($query);

        $ids = array_map(
            fn (DocumentInterface $document) => (string)$document->getId(),
            $childDocuments
        );

        return $this->documentStore->collect($ids);
    }
}
