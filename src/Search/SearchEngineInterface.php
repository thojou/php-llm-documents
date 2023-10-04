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

namespace Thojou\LLMDocuments\Search;

interface SearchEngineInterface
{
    /**
     * @param string $query
     * @param int    $limit
     *
     * @return array<int, SearchResultInterface>
     */
    public function search(string $query, int $limit): array;
}
