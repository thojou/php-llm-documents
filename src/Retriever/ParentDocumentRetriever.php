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

use Thojou\LLMDocuments\Splitter\SplitterInterface;
use Thojou\LLMDocuments\Storage\DocumentStore\DocumentStoreInterface;
use Thojou\LLMDocuments\Storage\VectorStore\VectorStoreInterface;

class ParentDocumentRetriever extends MultiVectorRetriever
{
    public function __construct(
        VectorStoreInterface $vectorStore,
        DocumentStoreInterface $documentStore,
        private readonly SplitterInterface $childSplitter,
        private readonly ?SplitterInterface $parentSplitter = null,
    ) {
        parent::__construct($vectorStore, $documentStore);
    }

    public function addDocuments(array $documents): void
    {
        if($this->parentSplitter) {
            $documents = $this->parentSplitter->splitDocuments($documents);
        }

        $this->documentStore->add($documents);

        foreach($documents as $document) {
            $childDocuments = $this->childSplitter->splitDocument($document);

            $this->vectorStore->addDocuments($childDocuments);
        }
    }
}
