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

use Exception;
use Thojou\LLMDocuments\Parser\ParserInterface;
use Thojou\LLMDocuments\Parser\Unstructured\Api\GeneralRequest;
use Thojou\LLMDocuments\Utils\File;
use Thojou\LLMDocuments\Utils\FileInterface;

abstract class UnstructuredParser implements ParserInterface
{
    public function __construct(
        private readonly Api\UnstructuredAPI $unstructured,
    ) {
    }

    /**
     * @throws Exception
     */
    public function parse(FileInterface|string $resource): string
    {
        $file = is_string($resource) ? new File($resource) : $resource;

        /**
         * @var array<array{type: string, text: string, metadata: array{link_texts: array<string>|null}}> $elements
         */
        $elements = (array)$this->unstructured->send(new GeneralRequest(
            $file->open()->stream(),
            strategy: $this->getStrategy()
        ));

        $file->close();

        return $this->parseElements($elements);
    }

    protected function getStrategy(): string
    {
        return 'auto';
    }

    /**
     * @param array<array{type: string, text: string, metadata: array{link_texts: array<string>|null}}> $elements
     *
     *
     * @return string
     */
    abstract protected function parseElements(array $elements): string;
}
