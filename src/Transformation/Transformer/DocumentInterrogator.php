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
use Thojou\LLMDocuments\ValueObjects\FunctionParameters;

class DocumentInterrogator extends OpenAIDocumentTransformer
{
    public function __construct(DoctranConfig $config)
    {
        parent::__construct($config);

        $items = new FunctionParameters();
        $items->add(
            new ExtractProperty(
                'question',
                'string',
                'The question.',
            )
        );
        $items->add(
            new ExtractProperty(
                'answer',
                'string',
                'The answer.',
            )
        );

        $this->functionParameters->add(
            new ExtractProperty(
                'questions_and_answers',
                'array',
                'The list of questions and answers',
                true,
                $items->toArray()
            )
        );
    }

    protected function getFunctionName(): string
    {
        return 'interrogate';
    }

    protected function getFunctionDescription(): string
    {
        return 'Convert a text document into a series of questions and answers';
    }
}
