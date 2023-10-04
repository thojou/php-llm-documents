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

namespace Thojou\LLMDocuments\Transformation;

use Thojou\LLMDocuments\Document\TransformedDocumentInterface;

interface DocumentTransformerInterface
{
    /**
     * @param TransformedDocumentInterface $document
     *
     * @return TransformedDocumentInterface
     */
    public function transform(TransformedDocumentInterface $document): TransformedDocumentInterface;
}
