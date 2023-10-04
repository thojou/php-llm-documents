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

class DocumentRefiner extends OpenAIDocumentTransformer
{
    /**
     * @param DoctranConfig $config
     * @param array<int, string>|null $topics
     */
    public function __construct(
        DoctranConfig $config,
        private readonly ?array $topics = null
    ) {
        parent::__construct($config);

        $this->functionParameters->add(
            new ExtractProperty(
                'refined_document',
                'string',
                'The document with irrelevant information removed.',
            )
        );
    }

    protected function getFunctionName(): string
    {
        return "refine";
    }

    protected function getFunctionDescription(): string
    {
        if ($this->topics) {
            return 'Remove all information from a document that is not relevant to the following topics: -' . join(
                ' -',
                $this->topics
            ) . '.';
        }

        return 'Remove all irrelevant information from a document.';
    }
}
