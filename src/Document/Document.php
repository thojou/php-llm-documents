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

class Document implements DocumentInterface
{
    /**
     * @param string               $pageContent
     * @param array<string, mixed> $metadata
     * @param string|null          $id
     */
    public function __construct(
        private string $pageContent = '',
        private array $metadata = [],
        ?string $id = null
    ) {
        if($id) {
            $this->setId($id);
        }
    }

    public function setId(string $id): DocumentInterface
    {
        $this->metadata[self::IDENTIFIER] = $id;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->metadata[self::IDENTIFIER] ?? null; // @phpstan-ignore-line
    }

    /**
     * @return string
     */
    public function getPageContent(): string
    {
        return $this->pageContent;
    }

    /**
     * @param string $pageContent
     *
     * @return Document
     */
    public function setPageContent(string $pageContent): Document
    {
        $this->pageContent = $pageContent;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * @param array<string, mixed> $metadata
     *
     * @return Document
     */
    public function mergeMetadata(array $metadata): Document
    {
        $this->metadata = array_merge_recursive($this->metadata, $metadata);

        return $this;
    }

    public function copy(): Document
    {
        return new Document(
            $this->getPageContent(),
            $this->getMetadata()
        );
    }
}
