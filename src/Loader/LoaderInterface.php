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

namespace Thojou\LLMDocuments\Loader;

use Thojou\LLMDocuments\Document\DocumentInterface;

/**
 * Interface for classes loading documents from a resource
 */
interface LoaderInterface
{
    /**
     * Load one ore more documents from a resource
     *
     * @param string $resource The resource to load the documents from
     *
     * @return DocumentInterface|array<int, DocumentInterface> The loaded document(s)
     */
    public function load(string $resource): DocumentInterface|array;
}
