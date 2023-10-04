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

namespace Thojou\LLMDocuments\Embedding;

/**
 * Interface for classes converting text into vector embeddings
 */
interface EmbeddingInterface
{
    /**
     * Convert a single text into a vector embedding
     *
     * @param string $text The text to be converted
     *
     * @return array<int, float> The vector embedding representing the text
     */
    public function embedString(string $text): array;

    /**
     * Convert a list of texts into a list of vector embeddings
     *
     * @param array<int, string> $list The list of texts to be converted
     *
     * @return array<int, array<int, float>> The list of vector embeddings representing the texts
     */
    public function embedList(array $list): array;
}
