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

namespace Thojou\LLMDocuments\Splitter;

use Exception;
use Thojou\LLMDocuments\Document\Document;
use Thojou\LLMDocuments\Document\DocumentInterface;

abstract class TextSplitter implements SplitterInterface
{
    public function __construct(
        protected int $chunkSize = 4000,
        protected int $chunkOverlap = 200,
        protected bool $keepSeparator = false,
    ) {
        if ($chunkOverlap > $chunkSize) {
            throw new Exception(
                "Got a larger chunk overlap ($chunkOverlap) than chunk size ($chunkSize), should be smaller."
            );
        }
    }

    /**
     * @param array<int, DocumentInterface> $documents
     *
     * @return array<int, DocumentInterface>
     */
    public function splitDocuments(array $documents): array
    {
        $splitDocuments = [];

        foreach($documents as $document) {
            $splitDocuments = array_merge($splitDocuments, $this->splitDocument($document));
        }

        return $splitDocuments;
    }

    public function splitDocument(DocumentInterface $document): array
    {
        $splits = $this->split($document->getPageContent());
        $documents = [];

        $index = -1;
        foreach($splits as $split) {
            $index = strpos($document->getPageContent(), $split, $index + 1);
            $documents[] = new Document(
                $split,
                $document->getMetadata() + [
                    'start_index' => $index,
                ]
            );
        }

        return $documents;
    }


    /**
     * @param array<int, string> $docs
     * @param string             $separator
     *
     * @return string|null
     */
    protected function joinDocs(array $docs, string $separator): ?string
    {
        $text = implode($separator, $docs);
        $text = trim($text);

        return $text === "" ? null : $text;
    }

    /**
     * @param array<int, string> $splits
     * @param string             $separator
     *
     * @return array<int, string>
     */
    protected function mergeSplit(array $splits, string $separator): array
    {
        $finalChunks = [];
        $currentChunks = [];
        $total = 0;

        foreach ($splits as $split) {
            $splitLength = strlen($split);
            $separatorLength = !empty($currentChunks) ? strlen($separator) : 0;

            if ($this->isChunkSizeExceeded($total, $splitLength, $separatorLength)) {
                if (!empty($currentChunks)) {
                    $doc = $this->joinDocs($currentChunks, $separator);

                    if ($doc !== null) {
                        $finalChunks[] = $doc;
                    }

                    while (
                        $total > $this->chunkOverlap
                        || $this->isChunkSizeExceeded($total, $splitLength, $separatorLength)
                    ) {
                        $total -= strlen($currentChunks[0]) + (count($currentChunks) > 1 ? $separatorLength : 0);
                        array_shift($currentChunks);
                    }
                }
            }
            $currentChunks[] = $split;
            $total += $splitLength + (count($currentChunks) > 1 ? $separatorLength : 0);
        }

        $doc = $this->joinDocs($currentChunks, $separator);

        if ($doc !== null) {
            $finalChunks[] = $doc;
        }

        return $finalChunks;
    }

    /**
     * @param string $text
     * @param string $separator
     * @param bool   $keepSeparator
     *
     * @return array<int, string>
     */
    protected function splitTextWithRegex(string $text, string $separator, bool $keepSeparator): array
    {
        $splits = [];

        if (!$separator) {
            return $this->filterEmptySplits(
                str_split($text)
            );
        }

        if (!$keepSeparator) {
            return $this->filterEmptySplits(
                preg_split("/" . preg_quote($separator) . "/", $text)
            );
        }

        $pattern = "/(" . preg_quote($separator) . ")/";
        $matches = (array)preg_split($pattern, $text, -1, PREG_SPLIT_DELIM_CAPTURE);

        foreach (array_chunk($matches, 2) as $pair) {
            $splits[] = implode('', $pair);
        }

        return $this->filterEmptySplits($splits);
    }

    /**
     * @param array<int, string>|false $splits
     *
     * @return array<int, string>
     */
    private function filterEmptySplits(array|false $splits): array
    {
        $splits = $splits !== false ? $splits : [];

        return array_filter($splits, function ($split) {
            return $split !== "";
        });
    }

    /**
     * @param int $total
     * @param int $splitLength
     * @param int $separatorLength
     *
     * @return bool
     */
    private function isChunkSizeExceeded(
        int $total,
        int $splitLength,
        int $separatorLength
    ): bool {
        return $total + $splitLength + $separatorLength > $this->chunkSize;
    }
}
