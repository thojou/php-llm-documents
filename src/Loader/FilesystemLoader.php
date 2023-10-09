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

namespace Thojou\LLMDocuments\Loader;

use Exception;
use Iterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Thojou\LLMDocuments\Document\Document;
use Thojou\LLMDocuments\Document\DocumentInterface;

class FilesystemLoader implements LoaderInterface
{
    public function __construct(
        private readonly ?string $pattern = null
    ) {
    }

    /**
     * @param string $resource
     *
     * @return DocumentInterface|array|DocumentInterface[]
     * @throws Exception
     */
    public function load(string $resource): DocumentInterface|array
    {
        if(!file_exists($resource)) {
            throw new Exception("Resource $resource does not exist");
        }

        if(is_file($resource)) {
            return $this->loadFile($resource);
        }

        $documents = [];
        $files = $this->setupIterator($resource);

        /** @var SplFileInfo|array{0: string} $file */
        foreach ($files as $file) {
            if($file instanceof SplFileInfo) {
                $documents[] = $this->loadFile((string)$file->getRealPath());
            } else {
                $documents[] = $this->loadFile((string)realpath($file[0]));
            }

        }

        return $documents;

    }

    private function loadFile(string $filepath): DocumentInterface
    {
        return new Document(
            (string)file_get_contents($filepath),
            [
                'source' => $filepath
            ]
        );
    }

    /**
     * @param string $resource
     *
     * @return Iterator
     */
    private function setupIterator(string $resource): Iterator
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($resource),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        if($this->pattern) {
            $iterator = new \RegexIterator(
                $iterator,
                $this->pattern,
                \RegexIterator::GET_MATCH
            );
        }

        return $iterator;
    }
}
