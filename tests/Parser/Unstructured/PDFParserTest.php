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

namespace Thojou\LLMDocuments\Tests\Parser\Unstructured;

use PHPUnit\Framework\TestCase;
use Thojou\LLMDocuments\Parser\Unstructured\Api\UnstructuredAPI;
use Thojou\LLMDocuments\Parser\Unstructured\HTMLParser;
use Thojou\LLMDocuments\Parser\Unstructured\PDFParser;
use Thojou\LLMDocuments\Utils\TempFile;

class PDFParserTest extends TestCase
{
    public function testParser(): void
    {
        $api = $this->createMock(UnstructuredAPI::class);
        $api
            ->expects($this->once())
            ->method('send')
            ->willReturn([[
                'type' => 'Title',
                'text' => 'Test content'
            ], [
                'type' => 'Title',
                'text' => 'Chapter 1',
                'metadata' => [
                    'link_texts' => ['Chapter 1']
                ]
            ], [
                'type' => 'NativeText',
                'text' => 'This text ',
            ], [
                'type' => 'NativeText',
                'text' => 'should combine',
            ],[
                'type' => 'Title',
                'text' => '',
            ]]);

        $parser = new PDFParser($api);
        $result = $parser->parse(TempFile::fromContent('Test content', 'application/pdf'));

        $this->assertEquals("Test content\n\nThis text should combine", $result);
    }

}
