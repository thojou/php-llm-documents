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

namespace Thojou\LLMDocuments\Tests\Document;

use PHPUnit\Framework\TestCase;
use Thojou\LLMDocuments\Document\Document;
use Thojou\LLMDocuments\Document\DocumentInterface;

class DocumentTest extends TestCase
{
    public function testDocument(): void
    {
        $metadata = [
            'source' => 'test.md',
        ];
        $document = new Document(
            "Test",
            $metadata
        );

        $this->assertInstanceOf(DocumentInterface::class, $document);
        $this->assertEquals('Test', $document->getPageContent());
        $this->assertEquals($metadata, $document->getMetadata());
    }

    public function testId(): void
    {
        $document = new Document("Test", ['source' => 'test.md']);

        $this->assertNull($document->getId());

        $document->setId('test123');

        $this->assertEquals('test123', $document->getId());
        $this->assertArrayHasKey('document_id', $document->getMetadata());
        $this->assertEquals('test123', $document->getMetadata()['document_id']);

        $document = new Document("Test", [], 'test123');
        $this->assertEquals('test123', $document->getId());
    }

    public function testCopy(): void
    {
        $document = new Document(
            "Test",
            [
                'source' => 'test.md',
            ]
        );

        $copy = $document->copy();

        $this->assertNotSame($document, $copy);
        $this->assertEquals($document->getPageContent(), $copy->getPageContent());
        $this->assertEquals($document->getMetadata(), $copy->getMetadata());
    }

    public function testMergeMetadata(): void
    {
        $document = new Document(
            "Test",
            [
                'source' => 'test.md',
            ]
        );

        $document->mergeMetadata([
            'title' => 'Test',
        ]);

        $this->assertEquals([
            'source' => 'test.md',
            'title' => 'Test',
        ], $document->getMetadata());

    }
}
