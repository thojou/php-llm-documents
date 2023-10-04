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

class TransformedDocument extends Document implements TransformedDocumentInterface
{
    /**
     * @param string                  $pageContent
     * @param array<string, mixed>    $metadata
     * @param array<array-key, mixed> $extractedProperties
     */
    public function __construct(
        string $pageContent = '',
        array $metadata = [],
        private array $extractedProperties = []
    ) {
        parent::__construct($pageContent, $metadata);
    }

    /**
     * @param DocumentInterface       $document
     * @param array<array-key, mixed> $extraProperties
     *
     * @return self
     */
    public static function fromDocument(DocumentInterface $document, array $extraProperties = []): self
    {
        return new self(
            $document->getPageContent(),
            $document->getMetadata(),
            $extraProperties
        );
    }

    /**
     * @return array<array-key, mixed>
     */
    public function getExtractedProperties(): array
    {
        return $this->extractedProperties;
    }

    /**
     * @param array<array-key, mixed> $extraProperties
     *
     * @return self
     */
    public function mergeExtraProperties(array $extraProperties): self
    {
        $this->extractedProperties = array_merge_recursive($this->extractedProperties, $extraProperties);

        return $this;
    }
}
