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

interface DocumentInterface
{
    public const IDENTIFIER = 'document_id';

    public function setId(string $id): self;

    public function getId(): ?string;

    public function setPageContent(string $pageContent): self;

    public function getPageContent(): string;

    /**
     * @return array<string, scalar>
     */
    public function getMetadata(): array;

    /**
     * @param array<string, scalar> $metadata
     *
     * @return self
     */
    public function mergeMetadata(array $metadata): self;

    public function copy(): self;
}
