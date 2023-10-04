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
use Thojou\LLMDocuments\Document\TransformedDocument;
use Thojou\LLMDocuments\Document\TransformedDocumentInterface;

class TransformedDocumentTest extends TestCase
{
    public function testTransformedDocument(): void
    {
        $document = new TransformedDocument(
            "Test",
            [],
            [ 'topics' => ['topic1'] ]
        );

        $this->assertInstanceOf(DocumentInterface::class, $document);
        $this->assertInstanceOf(TransformedDocumentInterface::class, $document);

        $document->mergeExtraProperties([
            'topics' => ['topic2'],
        ]);

        $this->assertEquals([
            'topics' => ['topic1', 'topic2'],
        ], $document->getExtractedProperties());
    }

    public function testFromDocument(): void
    {
        $document = new Document("Test", ['source' => 'test.md']);

        $transformedDocument = TransformedDocument::fromDocument($document, ['topics' => ['topic1']]);

        $this->assertInstanceOf(DocumentInterface::class, $transformedDocument);
        $this->assertInstanceOf(TransformedDocumentInterface::class, $transformedDocument);

        $transformedDocument->mergeExtraProperties(['topics' => ['topic2']]);

        $this->assertEquals(['topics' => ['topic1', 'topic2']], $transformedDocument->getExtractedProperties());
        $this->assertEquals(['source' => 'test.md'], $transformedDocument->getMetadata());
        $this->assertEquals('Test', $transformedDocument->getPageContent());
    }

}
