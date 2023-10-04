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

class DocumentSummarizer extends OpenAIDocumentTransformer
{
    public function __construct(
        DoctranConfig $config,
        private readonly int $tokenLimit
    ) {
        parent::__construct($config);

        $this->functionParameters->add(
            new ExtractProperty(
                'summary',
                'string',
                'The summary of the document.',
            )
        );
    }

    protected function getFunctionName(): string
    {
        return 'summarize';
    }

    protected function getFunctionDescription(): string
    {
        return sprintf('Summarize a document in under %s tokens.', $this->tokenLimit);
    }
}
