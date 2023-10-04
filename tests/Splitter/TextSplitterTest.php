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

namespace Thojou\LLMDocuments\Tests\Splitter;

use Exception;
use Monolog\Test\TestCase;
use Thojou\LLMDocuments\Document\DocumentInterface;
use Thojou\LLMDocuments\Splitter\TextSplitter;

class TextSplitterTest extends TestCase
{
    public function testEnsureChunkSizeGreaterOverlap(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Got a larger chunk overlap (9) than chunk size (5), should be smaller.");

        $this->getMockBuilder(TextSplitter::class)
            ->setConstructorArgs([5, 9])
            ->onlyMethods(['split'])
            ->getMock();
    }

    public function testSplitDocument(): void
    {
        $splitter = $this->createPartialMock(TextSplitter::class, ['split']);
        $document = $this->createMock(DocumentInterface::class);

        $document
            ->method('getPageContent')
            ->willReturn('Hallo Welt');


        $splitter->expects($this->once())
            ->method('split')
            ->with('Hallo Welt')
            ->willReturn(['Hallo ', 'Welt']);

        $result = $splitter->splitDocument($document);

        $this->assertCount(2, $result);
    }



    public function testSplitDocuments(): void
    {
        $splitter = $this->createPartialMock(TextSplitter::class, ['split', 'splitDocument']);
        $document = $this->createMock(DocumentInterface::class);

        $splitter->expects($this->once())
            ->method('splitDocument')
            ->with($document)
            ->willReturn(['Hallo ', 'Welt']);

        $result = $splitter->splitDocuments([$document]);

        $this->assertCount(2, $result);
    }

}
