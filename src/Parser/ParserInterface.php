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

namespace Thojou\LLMDocuments\Parser;

use Thojou\LLMDocuments\Utils\FileInterface;

interface ParserInterface
{
    /**
     * @param FileInterface|string $resource
     *
     * @return string
     */
    public function parse(FileInterface|string $resource): string;
}
