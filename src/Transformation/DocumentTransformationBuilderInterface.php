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

use Thojou\LLMDocuments\Document\DocumentInterface;
use Thojou\LLMDocuments\ValueObjects\ExtractProperty;

interface DocumentTransformationBuilderInterface
{
    public function execute(DocumentInterface $document): DocumentInterface;

    /**
     * @param array<int, ExtractProperty> $properties
     */
    public function extract(array $properties): static;

    public function summarize(int $tokenLimit): static;

    /**
     * @param array<int, string>|null $topics
     */
    public function refine(?array $topics = null): static;

    public function translate(string $language): static;

    public function interrogate(): static;
}
