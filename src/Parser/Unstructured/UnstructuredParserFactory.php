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

namespace Thojou\LLMDocuments\Parser\Unstructured;

use Thojou\LLMDocuments\Parser\ParserFactoryInterface;
use Thojou\LLMDocuments\Parser\ParserInterface;

class UnstructuredParserFactory implements ParserFactoryInterface
{
    public function __construct(
        private readonly Api\UnstructuredAPI $unstructured,
    ) {
    }

    public function create(?string $type = null): ParserInterface
    {
        return match ($type) {
            'application/pdf' => new PDFParser($this->unstructured),
            default => new HTMLParser($this->unstructured),
        };
    }
}
