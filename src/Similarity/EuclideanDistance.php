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

namespace Thojou\LLMDocuments\Similarity;

class EuclideanDistance implements SimilarityInterface
{
    public function compare(array $queryVector, array $vector): float
    {
        $sumOfSquares = 0;

        for ($i = 0; $i < count($queryVector); $i++) {
            $sumOfSquares += ($queryVector[$i] - $vector[$i]) ** 2;
        }

        return sqrt($sumOfSquares);
    }

    public function getScore(array $queryVector, array $vector): float
    {
        $distance = $this->compare($queryVector, $vector);

        // Normalize the distance to a similarity score between 0 and 1
        return 1 / (1 + $distance);
    }
}
