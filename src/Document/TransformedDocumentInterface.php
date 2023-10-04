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

namespace Thojou\LLMDocuments\Document;

interface TransformedDocumentInterface extends DocumentInterface
{
    /**
     * @return array<array-key, mixed>
     */
    public function getExtractedProperties(): array;

    /**
     * @param array<array-key, mixed> $extraProperties
     *
     * @return self
     */
    public function mergeExtraProperties(array $extraProperties): self;
}
