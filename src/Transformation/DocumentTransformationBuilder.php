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
use Thojou\LLMDocuments\Document\TransformableDocumentInterface;
use Thojou\LLMDocuments\Document\TransformedDocument;
use Thojou\LLMDocuments\Document\TransformedDocumentInterface;
use Thojou\LLMDocuments\Transformation\Transformer\DocumentExtractor;
use Thojou\LLMDocuments\Transformation\Transformer\DocumentInterrogator;
use Thojou\LLMDocuments\Transformation\Transformer\DocumentRefiner;
use Thojou\LLMDocuments\Transformation\Transformer\DocumentSummarizer;
use Thojou\LLMDocuments\Transformation\Transformer\DocumentTranslator;
use Thojou\LLMDocuments\ValueObjects\DoctranConfig;
use Thojou\LLMDocuments\ValueObjects\ExtractProperty;

class DocumentTransformationBuilder implements DocumentTransformationBuilderInterface
{
    /**
     * @var array<int, DocumentTransformerInterface>
     */
    private array $transformers = [];

    public function __construct(
        private readonly DoctranConfig $config
    ) {
    }

    public function execute(DocumentInterface $document): TransformedDocumentInterface
    {
        $transformedDocument = TransformedDocument::fromDocument($document->copy());

        foreach($this->transformers as $transformer) {
            $transformedDocument = $transformer->transform($transformedDocument);
        }

        return $transformedDocument;
    }

    /**
     * @param array<int, ExtractProperty> $properties
     *
     * @return $this
     */
    public function extract(array $properties): static
    {
        $this->transformers[] = new DocumentExtractor($this->config, $properties);

        return $this;
    }

    public function summarize(int $tokenLimit): static
    {
        $this->transformers[] = new DocumentSummarizer($this->config, $tokenLimit);

        return $this;
    }

    /**
     * @param array<int, string>|null $topics
     *
     * @return $this
     */
    public function refine(?array $topics = null): static
    {
        $this->transformers[] = new DocumentRefiner($this->config, $topics);

        return $this;
    }

    public function translate(string $language): static
    {
        $this->transformers[] = new DocumentTranslator($this->config, $language);

        return $this;
    }

    public function interrogate(): static
    {
        $this->transformers[] = new DocumentInterrogator($this->config);

        return $this;
    }
}
