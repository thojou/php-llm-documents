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

namespace Thojou\LLMDocuments\Retriever;

use Thojou\LLMDocuments\Document\DocumentInterface;

interface RetrieverInterface
{
    /**
     * @param string $query
     *
     * @return array<int, DocumentInterface>
     */
    public function getRelevantDocuments(string $query): array;

    /**
     * @param array<int, DocumentInterface> $documents
     *
     * @return void
     */
    public function addDocuments(array $documents): void;
}
