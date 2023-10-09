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

namespace Thojou\LLMDocuments\Tests\Loader;

use Thojou\LLMDocuments\Document\DocumentInterface;
use Thojou\LLMDocuments\Loader\FilesystemLoader;
use PHPUnit\Framework\TestCase;

class FilesystemLoaderTest extends TestCase
{
    public function testLoadFailed(): void
    {
        $this->expectException(\Exception::class);

        $loader = new FilesystemLoader();
        $loader->load('not-existing-file');
    }

    public function testLoadFile(): void
    {
        $loader = new FilesystemLoader();
        $document = $loader->load(__DIR__ . '/../../README.md');

        $this->assertInstanceOf(DocumentInterface::class, $document);
        $this->assertStringContainsString('PHP LLM Documents', $document->getPageContent());
    }

    public function testLoadDirectory(): void
    {
        $loader = new FilesystemLoader();
        $documents = $loader->load(__DIR__ . '/../../examples');

        $this->assertIsArray($documents);
        $this->assertGreaterThan(0, count($documents));
    }

    public function testLoadDirectoryWithPattern(): void
    {
        $loader = new FilesystemLoader('/.*.md/');
        $documents = $loader->load(__DIR__ . '/../../examples');

        $this->assertIsArray($documents);
        $this->assertCount(1, $documents);
    }

}
