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

use PHPUnit\Framework\TestCase;
use Thojou\LLMDocuments\Splitter\RecursiveTextSplitter;

class RecursiveTextSplitterTest extends TestCase
{
    public function testSplitTextWithSeparator(): void
    {
        $splitter = new RecursiveTextSplitter(["\n"], 5, 0);
        $result = $splitter->split("This is a\n\ntest text.");
        $this->assertEquals([
            "This is a\n",
            "test text."
        ], $result);
    }

    public function testSplitTextWithSeparatorAndEmptyString(): void
    {
        $splitter = new RecursiveTextSplitter(["\n"], 5, 0);
        $result = $splitter->split("");
        $this->assertEquals(0, count($result));
    }

    public function testSplitTextWithoutKeepingSeparator(): void
    {
        $splitter = new RecursiveTextSplitter(["\n"], 5, 0, false);
        $result = $splitter->split("This is a\n\ntest text.");
        $this->assertEquals([
            "This is a",
            "test text."
        ], $result);
    }

    public function testSplitTextWithEmptySeparator(): void
    {
        $splitter = new RecursiveTextSplitter([""], 5, 0);
        $result = $splitter->split("Hello");
        $this->assertEquals(1, count($result));
    }

    public function testSplitTextWithMultipleSeparators(): void
    {
        $splitter = new RecursiveTextSplitter(["\n", ""], 5, 0);
        $result = $splitter->split("Hello\nHow are you doing");
        $this->assertEquals(5, count($result));
    }

    public function testSplitTextWithOverlap(): void
    {
        $splitter = new RecursiveTextSplitter([" "], 10, 4);
        $result = $splitter->split("Hello How are you doing");
        $this->assertEquals([
            "Hello How",
            "How are",
            "are you",
            "you doing",
        ], $result);
    }

    public function testSplitTextWithoutMatchingSeparator(): void
    {
        $splitter = new RecursiveTextSplitter(["~"], 10, 0);
        $result = $splitter->split("Hello How are you doing");
        $this->assertCount(3, $result);
    }
}
