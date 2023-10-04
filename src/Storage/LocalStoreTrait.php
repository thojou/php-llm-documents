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

namespace Thojou\LLMDocuments\Storage;

trait LocalStoreTrait
{
    protected readonly string $filename;

    /**
     * @var array<string, array{pageContent: string, metadata: array<string, mixed>}>
     */
    protected array $storage;

    private function load(): void
    {
        $this->storage = [];

        if (file_exists($this->filename)) {
            $this->storage = (array)json_decode((string)file_get_contents($this->filename), true); // @phpstan-ignore-line
        }
    }

    private function save(): void
    {
        file_put_contents($this->filename, json_encode($this->storage, JSON_PRETTY_PRINT));
    }
}
