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
use Thojou\LLMDocuments\Retriever\SimilarityRetriever;
use Thojou\LLMDocuments\Storage\VectorStore\VectorStoreInterface;

class SimilarityRetrieverTest extends TestCase
{
    public function testRetriever(): void
    {
        $document = $this->createMock(DocumentInterface::class);
        $vectorStore = $this->createMock(VectorStoreInterface::class);

        $vectorStore
            ->method('addDocuments')
            ->with([$document]);

        $vectorStore
            ->method('search')
            ->with('test', 0.75, 4)
            ->willReturn([$document]);


        $retriever = new SimilarityRetriever($vectorStore);
        $retriever->addDocuments([$document]);
        $result = $retriever->getRelevantDocuments('test');

        $this->assertEquals([$document], $result);
    }
}
