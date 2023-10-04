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

namespace Thojou\LLMDocuments\Tests\Storage;

use Thojou\LLMDocuments\Storage\LocalStoreTrait;
use PHPUnit\Framework\TestCase;

class LocalStoreTraitTest extends TestCase
{
    use LocalStoreTrait;

    private string $testFilename;

    protected function setUp(): void
    {
        $this->testFilename = 'test.json';
        $this->filename = $this->testFilename;
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testFilename)) {
            unlink($this->testFilename);
        }
    }

    public function testLoad(): void
    {
        // Test case when file exists and contains valid JSON data
        $data = ['pageContent' => 'Test', 'metadata' => ['foo' => 'bar']];
        file_put_contents($this->testFilename, json_encode($data));
        $this->load();
        $this->assertEquals($data, $this->storage);

        // Test case when file exists but contains invalid JSON data
        file_put_contents($this->testFilename, 'invalid-json-data');
        $this->load();
        $this->assertEmpty($this->storage);

        // Test case when file does not exist
        unlink($this->testFilename);
        $this->load();
        $this->assertEmpty($this->storage);
    }

    public function testSave(): void
    {
        // Test case when file is saved successfully
        $data = [
            'id1' => ['pageContent' => 'Test', 'metadata' => ['foo' => 'bar']]
        ];

        $this->storage = $data;
        $this->save();
        $this->assertTrue(file_exists($this->testFilename));
        $this->assertEquals(json_encode($data, JSON_PRETTY_PRINT), file_get_contents($this->testFilename));
    }


}
