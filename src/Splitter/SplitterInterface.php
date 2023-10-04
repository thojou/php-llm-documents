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

namespace Thojou\LLMDocuments\Splitter;

use Thojou\LLMDocuments\Document\DocumentInterface;

interface SplitterInterface
{
    /**
     * @param array<int, DocumentInterface> $documents
     *
     * @return array<int, DocumentInterface>
     */
    public function splitDocuments(array $documents): array;


    /**
     * @param DocumentInterface $document
     *
     * @return array<int, DocumentInterface>
     */
    public function splitDocument(DocumentInterface $document): array;

    /**
     * @param string $text
     *
     * @return array<int, string>
     */
    public function split(string $text): array;
}
