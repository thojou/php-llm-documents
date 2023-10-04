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

namespace Thojou\LLMDocuments\Tests\Retriever;

use PHPUnit\Framework\TestCase;
use Thojou\LLMDocuments\Document\DocumentInterface;
use Thojou\LLMDocuments\Retriever\MultiVectorRetriever;
use Thojou\LLMDocuments\Retriever\ParentDocumentRetriever;
use Thojou\LLMDocuments\Splitter\SplitterInterface;
use Thojou\LLMDocuments\Storage\DocumentStore\DocumentStoreInterface;
use Thojou\LLMDocuments\Storage\VectorStore\VectorStoreInterface;

class ParentDocumentRetrieverTest extends TestCase
{
    public function testRetriever(): void
    {
        $document = $this->createMock(DocumentInterface::class);
        $vectorStore = $this->createMock(VectorStoreInterface::class);
        $documentStore = $this->createMock(DocumentStoreInterface::class);
        $childSplitter = $this->createMock(SplitterInterface::class);
        $parentSplitter = $this->createMock(SplitterInterface::class);

        $parentSplitter
            ->expects($this->once())
            ->method('splitDocuments')
            ->with([$document])
            ->willReturn([$document]);

        $childSplitter
            ->expects($this->once())
            ->method('splitDocument')
            ->with($document)
            ->willReturn([$document]);

        $documentStore
            ->expects($this->once())
            ->method('add')
            ->with([$document]);

        $vectorStore
            ->expects($this->once())
            ->method('addDocuments')
            ->with([$document]);

        $retriever = new ParentDocumentRetriever($vectorStore, $documentStore, $childSplitter, $parentSplitter);
        $retriever->addDocuments([$document]);
    }
}
