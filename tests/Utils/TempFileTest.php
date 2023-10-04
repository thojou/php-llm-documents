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

namespace Thojou\LLMDocuments\Tests\Utils;

use Exception;
use Thojou\LLMDocuments\Utils\TempFile;
use PHPUnit\Framework\TestCase;

class TempFileTest extends TestCase
{
    public function testFromContent(): void
    {
        $content = "Hello, world!";
        $mimeType = 'text/plain';

        $tempFile = TempFile::fromContent($content, $mimeType);

        $this->assertInstanceOf(TempFile::class, $tempFile);
        $this->assertFileExists($tempFile->getFilename());
        $this->assertEquals($content, file_get_contents($tempFile->getFilename()));

        $tempFile->close();
        $this->assertFileDoesNotExist($tempFile->getFilename());
    }

    public function testInvalidMimeType(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Could not guess file extension for mime type invalid/mime-type');

        TempFile::fromContent("Hello, world!", 'invalid/mime-type');
    }


}
