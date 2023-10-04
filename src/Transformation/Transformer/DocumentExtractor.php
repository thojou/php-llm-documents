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

class DocumentExtractor extends OpenAIDocumentTransformer
{
    /**
     * @param DoctranConfig               $config
     * @param array<int, ExtractProperty> $properties
     */
    public function __construct(
        DoctranConfig $config,
        array $properties
    ) {
        parent::__construct($config);

        foreach ($properties as $property) {
            $this->functionParameters->add($property);
        }
    }

    protected function getFunctionName(): string
    {
        return 'extract_information';
    }

    protected function getFunctionDescription(): string
    {
        return 'Extract structured data from raw text document.';
    }
}
