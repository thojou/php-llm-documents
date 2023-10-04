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

namespace Thojou\LLMDocuments\Tests\Similarity;

use Thojou\LLMDocuments\Similarity\CosineSimilarity;
use PHPUnit\Framework\TestCase;

class CosineSimilarityTest extends TestCase
{
    /**
     * Data provider for testCompare and testGetScore methods.
     *
     * @return array<int, array<int, array<int, float>|float>>
     */
    public static function similarityDataProvider(): array
    {
        return [
            // Test vectors with different degrees of similarity
            [[1, 2, 3], [4, 5, 6], 0.9746318],
            [[1, 2, 3], [1, 2, 3], 1.0],
            [[1, 2, 3], [7, 8, 9], 0.9594119],
            [[1, 0, 0], [0, 1, 0], 0.0],
            [[1, 2, 3], [0, 0, 0], 0.0],
        ];
    }

    /**
     * Test the compare method with valid input.
     *
     * @dataProvider similarityDataProvider
     *
     * @param array<int, float> $queryVector
     * @param array<int, float> $vector
     * @param float $expectedSimilarity
     */
    public function testCompare(array $queryVector, array $vector, float $expectedSimilarity): void
    {
        $cosineSimilarity = new CosineSimilarity();
        $result = $cosineSimilarity->compare($queryVector, $vector);
        $this->assertEquals($expectedSimilarity, number_format($result, 7), '');
    }

    /**
     * Test the getScore method with valid input.
     *
     * @dataProvider similarityDataProvider
     *
     * @param array<int, float> $queryVector
     * @param array<int, float> $vector
     * @param float $expectedSimilarity
     */
    public function testGetScore(array $queryVector, array $vector, float $expectedSimilarity): void
    {
        $cosineSimilarity = new CosineSimilarity();
        $result = $cosineSimilarity->getScore($queryVector, $vector);
        $this->assertEquals($expectedSimilarity, number_format($result, 7), '');
    }
}
