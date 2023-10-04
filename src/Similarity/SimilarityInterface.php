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

interface SimilarityInterface
{
    /**
     * @param array<int, float> $queryVector
     * @param array<int, float> $vector
     *
     * @return float
     */
    public function compare(array $queryVector, array $vector): float;

    /**
     * @param array<int, float> $queryVector
     * @param array<int, float> $vector
     *
     * @return float
     */
    public function getScore(array $queryVector, array $vector): float;
}
