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

class CosineSimilarity implements SimilarityInterface
{
    public function compare(array $queryVector, array $vector): float
    {
        $dotProduct = 0;
        $queryMagnitude = 0;
        $vectorMagnitude = 0;

        for ($i = 0; $i < count($queryVector); $i++) {
            $dotProduct += $queryVector[$i] * $vector[$i];
            $queryMagnitude += $queryVector[$i] ** 2;
            $vectorMagnitude += $vector[$i] ** 2;
        }

        $queryMagnitude = sqrt($queryMagnitude);
        $vectorMagnitude = sqrt($vectorMagnitude);

        if($queryMagnitude == 0 || $vectorMagnitude == 0) {
            return 0;
        }

        return $dotProduct / ($queryMagnitude * $vectorMagnitude);
    }

    public function getScore(array $queryVector, array $vector): float
    {
        return $this->compare($queryVector, $vector);
    }
}
