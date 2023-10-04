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
use Thojou\LLMDocuments\Storage\DocumentStore\DocumentStoreInterface;
use Thojou\LLMDocuments\Storage\VectorStore\VectorStoreInterface;

class MultiVectorRetrieverTest extends TestCase
{
    public function testRetriever(): void
    {
        $document = $this->createMock(DocumentInterface::class);
        $vectorStore = $this->createMock(VectorStoreInterface::class);
        $documentStore = $this->createMock(DocumentStoreInterface::class);

        $document
            ->method('getId')
            ->willReturn('test123');

        $vectorStore
            ->expects($this->once())
            ->method('search')
            ->with('test', 0.75, 4)
            ->willReturn([$document]);

        $documentStore
            ->expects($this->once())
            ->method('collect')
            ->with($this->equalTo(['test123']))
            ->willReturn([$document]);

        $retriever = $this->getMockBuilder(MultiVectorRetriever::class)
            ->setConstructorArgs([$vectorStore, $documentStore])
            ->onlyMethods(['addDocuments'])
            ->getMock();

        $result = $retriever->getRelevantDocuments('test');

        $this->assertEquals([$document], $result);
    }
}
