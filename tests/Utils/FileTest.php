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
use PHPUnit\Framework\TestStatus\Warning;
use Thojou\LLMDocuments\Utils\File;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    private const TEST_FILENAME = '/tmp/test_file.txt';

    protected function setUp(): void
    {
        file_put_contents(self::TEST_FILENAME, 'Test file content');
    }

    protected function tearDown(): void
    {
        unlink(self::TEST_FILENAME);
    }

    public function testOpenExistingFile(): void
    {
        $file = new File(self::TEST_FILENAME);

        $this->assertTrue($file->exists());

        $openedFile = $file->open();

        $this->assertInstanceOf(File::class, $openedFile);
        $this->assertIsResource($file->stream());

        $file->close();
    }

    public function testOpenNonExistingFile(): void
    {
        $nonExistingFile = new File('non_existing_file.txt');

        $this->assertFalse($nonExistingFile->exists());

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('File non_existing_file.txt does not exist');

        $nonExistingFile->open();
    }

    public function testClose(): void
    {
        $file = new File(self::TEST_FILENAME);
        $file->open();

        $this->assertIsResource($file->stream());

        $file->close();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('File /tmp/test_file.txt is not open');

        $file->stream();
    }

    public function testGetFilename(): void
    {
        $file = new File(self::TEST_FILENAME);

        $this->assertEquals(self::TEST_FILENAME, $file->getFilename());
    }

    public function testGetContent(): void
    {
        $file = new File(self::TEST_FILENAME);
        $file->open();

        $expectedContent = 'Test file content';
        $this->assertEquals($expectedContent, $file->getContent());

        $file->close();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('File /tmp/test_file.txt is not open');

        $file->getContent();
    }

    public function testUnableToOpenFile(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Could not open file /tmp/test_file.txt');

        $file = new File(self::TEST_FILENAME);
        @$file->open('invalid_mode');
    }
}
