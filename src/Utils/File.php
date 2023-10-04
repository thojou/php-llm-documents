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

use Exception;

class File implements FileInterface
{
    /**
     * @var resource|null
     */
    protected $resource = null;

    public function __construct(
        protected readonly string $filename,
    ) {
    }

    public function __destruct()
    {
        $this->close();
    }

    /**
     * @throws Exception
     */
    public function open(string $mode = 'r'): self
    {
        if(!$this->exists()) {
            throw new Exception('File ' . $this->filename . ' does not exist');
        }

        $resource = fopen($this->filename, $mode);

        if(!is_resource($resource)) {
            throw new \Exception('Could not open file ' . $this->filename);
        }

        $this->resource = $resource;

        return $this;
    }

    public function close(): void
    {
        if(!is_resource($this->resource)) {
            return;
        }

        fclose($this->resource);
    }

    public function exists(): bool
    {
        return is_file($this->filename);
    }

    /**
     * @return resource
     * @throws Exception
     */
    public function stream(): mixed
    {
        if(!is_resource($this->resource)) {
            throw new Exception('File ' . $this->filename . ' is not open');
        }

        return $this->resource;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getContent(): string
    {
        if(!is_resource($this->resource)) {
            throw new Exception('File ' . $this->filename . ' is not open');
        }

        fseek($this->resource, 0);
        $content = stream_get_contents($this->resource);
        fseek($this->resource, 0);

        return (string)$content;
    }
}
