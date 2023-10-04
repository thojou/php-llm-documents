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

namespace Thojou\LLMDocuments\Storage\DocumentStore;

use Thojou\LLMDocuments\Document\DocumentInterface;
use Thojou\LLMDocuments\Storage\StorageInterface;

interface DocumentStoreInterface
{
    /**
     * @param DocumentInterface|array<DocumentInterface> $documents
     *
     * @return void
     */
    public function add(DocumentInterface|array $documents): void;

    /**
     * @param string $id
     *
     * @return DocumentInterface
     */
    public function get(string $id): DocumentInterface;

    /**
     * @param array<string> $ids
     *
     * @return array<DocumentInterface>
     */
    public function collect(array $ids): array;

    /**
     * @param array<int, string> $ids
     *
     * @return void
     */
    public function delete(array $ids): void;
}
