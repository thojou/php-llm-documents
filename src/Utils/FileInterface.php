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

namespace Thojou\LLMDocuments\Utils;

interface FileInterface
{
    /**
     * @param string $mode
     *
     * @return static
     */
    public function open(string $mode = 'rb'): self;

    public function close(): void;

    public function exists(): bool;

    /**
     * @return resource
     */
    public function stream(): mixed;

    public function getContent(): string;

    public function getFilename(): string;
}
