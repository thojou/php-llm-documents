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

use Thojou\LLMDocuments\Utils\Cleaner;
use PHPUnit\Framework\TestCase;

class CleanerTest extends TestCase
{
    public function testCleanNonAsciiChars(): void
    {
        $input = "Héllò Wörld!";
        $expectedOutput = "Hll Wrld!";

        $output = Cleaner::cleanNonAsciiChars($input);

        $this->assertEquals($expectedOutput, $output);
    }

    public function testCleanExtraWhitespace(): void
    {
        $input = "  Trim  \n   extra   \t  whitespace  ";
        $expectedOutput = "Trim \n extra whitespace";

        $output = Cleaner::cleanExtraWhitespace($input);

        $this->assertEquals($expectedOutput, $output);
    }

    public function testCleanNewLines(): void
    {
        $input = "Line 1\n\nLine 2\nLine 3\n";
        $expectedOutput = "Line 1\n\nLine 2 Line 3";

        $output = Cleaner::cleanNewLines($input);

        $this->assertEquals($expectedOutput, $output);
    }

    public function testCleanBulletPoints(): void
    {
        $input = "• Bullet point 1\n• Bullet point 2";
        $expectedOutput = "* Bullet point 1\n* Bullet point 2";

        $output = Cleaner::cleanBulletPoints($input);

        $this->assertEquals($expectedOutput, $output);
    }

    public function testClean(): void
    {
        $input = "  This is an example text  \n  with some extra whitespace.  \n\n  And bullet points:  \n• Point 1\n• Point 2";
        $expectedOutput = "This is an example text with some extra whitespace.\n\nAnd bullet points: \n* Point 1\n* Point 2";

        $output = Cleaner::clean($input, [
            'cleanBulletPoints',
            'cleanExtraWhitespace',
            'cleanNewLines',
        ]);

        $this->assertEquals($expectedOutput, $output);
    }

}
