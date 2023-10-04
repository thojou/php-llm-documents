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
use Thojou\LLMDocuments\Parser\Unstructured\UnstructuredParserFactory;

class UnstructuredParserFactoryTest extends TestCase
{
    public function testFactory(): void
    {
        $api = $this->createMock(UnstructuredAPI::class);

        $factory = new UnstructuredParserFactory($api);

        $this->assertInstanceOf(PDFParser::class, $factory->create('application/pdf'));
        $this->assertInstanceOf(HTMLParser::class, $factory->create('text/html'));
        $this->assertInstanceOf(HTMLParser::class, $factory->create('text/plain'));
    }

}
