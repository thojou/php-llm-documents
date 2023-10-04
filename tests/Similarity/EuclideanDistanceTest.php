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

use Thojou\LLMDocuments\Similarity\EuclideanDistance;
use PHPUnit\Framework\TestCase;

class EuclideanDistanceTest extends TestCase
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
            [[1, 2, 3], [4, 5, 6], 5.1961524, 0.1613905],
            [[1, 2, 3], [1, 2, 3], 0.0, 1],
            [[1, 2, 3], [7, 8, 9], 10.3923048, 0.0877785],
        ];
    }

    /**
     * Test the compare method with valid input.
     *
     * @dataProvider similarityDataProvider
     *
     * @param array<int, float> $queryVector
     * @param array<int, float> $vector
     * @param float $expectedDistance
     */
    public function testCompare(array $queryVector, array $vector, float $expectedDistance): void
    {
        $euclidean = new EuclideanDistance();
        $result = $euclidean->compare($queryVector, $vector);
        $this->assertEquals($expectedDistance, number_format($result, 7), '');
    }

    /**
     * Test the getScore method with valid input.
     *
     * @dataProvider similarityDataProvider
     *
     * @param array<int, float> $queryVector
     * @param array<int, float> $vector
     * @param float $expectedDistance
     * @param float $expectedScore
     */
    public function testGetScore(array $queryVector, array $vector, float $expectedDistance, float $expectedScore): void
    {
        $euclidean = new EuclideanDistance();
        $result = $euclidean->getScore($queryVector, $vector);
        $this->assertEquals($expectedScore, number_format($result, 7), '');
    }

}
