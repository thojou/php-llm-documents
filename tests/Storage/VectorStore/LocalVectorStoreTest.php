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

namespace Thojou\LLMDocuments\Tests\Storage\VectorStore;

use Exception;
use Thojou\LLMDocuments\Document\DocumentInterface;
use Thojou\LLMDocuments\Embedding\EmbeddingInterface;
use Thojou\LLMDocuments\Storage\VectorStore\LocalVectorStore;
use PHPUnit\Framework\TestCase;

class LocalVectorStoreTest extends TestCase
{
    public function setUp(): void
    {
        if(is_file('/tmp/test.json')) {
            unlink('/tmp/test.json');
        }
    }

    public function testAddDocument(): void
    {
        $embeddings = $this->createMock(EmbeddingInterface::class);
        $document = $this->createMock(DocumentInterface::class);

        $document->method('getPageContent')->willReturn('This is document 1.');
        $document->method('getMetadata')->willReturn([DocumentInterface::IDENTIFIER => '1']);

        $embeddings->method('embedList')->willReturn([[1, 2, 3]]);
        $embeddings->method('embedString')->willReturn([1, 2, 3]);

        $store = new LocalVectorStore('/tmp/test.json', $embeddings);

        $store->addDocuments([$document]);
        $result = $store->search('This is document 1.');
        $this->assertCount(1, $result);

        $store->delete(['1']);
        $result2 = $store->search('This is document 1.');
        $this->assertCount(0, $result2);
    }

    public function testExceptionOnInvalidIdKeyType(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Metadata key document_id must be a string or null');

        $embeddings = $this->createMock(EmbeddingInterface::class);
        $document = $this->createMock(DocumentInterface::class);

        $document->method('getPageContent')->willReturn('This is document 1.');
        $document->method('getMetadata')->willReturn([DocumentInterface::IDENTIFIER => false]);

        $embeddings->method('embedList')->willReturn([[1, 2, 3]]);

        $store = new LocalVectorStore('/tmp/test.json', $embeddings);
        $store->addDocuments([$document]);
    }

}
