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

namespace Thojou\LLMDocuments\Transformation\Transformer;

use Thojou\LLMDocuments\ValueObjects\DoctranConfig;
use Thojou\LLMDocuments\ValueObjects\ExtractProperty;

class DocumentTranslator extends OpenAIDocumentTransformer
{
    public function __construct(
        DoctranConfig $config,
        private readonly string $language
    ) {
        parent::__construct($config);

        $this->functionParameters->add(
            new ExtractProperty(
                'translated_document',
                'string',
                sprintf('The document translated into %s.', $this->language),
            )
        );
    }

    protected function getFunctionName(): string
    {
        return 'translate';
    }

    protected function getFunctionDescription(): string
    {
        return sprintf('Translate a document into %s.', $this->language);
    }
}
