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

namespace Thojou\LLMDocuments\Tests\Storage\DocumentStore;

use Thojou\LLMDocuments\Document\DocumentInterface;
use Thojou\LLMDocuments\Storage\DocumentStore\LocalDocumentStore;
use PHPUnit\Framework\TestCase;

class LocalDocumentStoreTest extends TestCase
{
    protected function setUp(): void
    {
        if (is_file('/tmp/test.json')) {
            unlink('/tmp/test.json');
        }
    }

    public function testStoreSingleDocument(): void
    {
        $store = new LocalDocumentStore('/tmp/test.json');

        $document = $this->createMock(DocumentInterface::class);

        $document->method('getPageContent')->willReturn('This is document 1.');
        $document->method('getId')->willReturn('1');

        $store->add($document);
        $this->assertEquals($document->getPageContent(), $store->get('1')->getPageContent());
    }

    public function testStoreMultipleDocuments(): void
    {
        $store = new LocalDocumentStore('/tmp/test.json');

        $document = $this->createMock(DocumentInterface::class);

        $document->method('getPageContent')->willReturn('This is document 1.');
        $document->method('getId')->willReturn('1');

        $store->add([$document]);
        $this->assertEquals($document->getPageContent(), $store->collect(['1'])[0]->getPageContent());
        $store->delete(['1']);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Document with id 1 not found');
        $store->collect(['1']);
    }

}
