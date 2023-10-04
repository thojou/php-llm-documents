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

class RecursiveTextSplitter extends TextSplitter
{
    /**
     * @param array<int, string> $separators
     * @param bool                    $isSeparatorRegex
     * @param int                     $chunkSize
     * @param int                     $chunkOverlap
     * @param bool                    $keepSeparator
     *
     * @throws Exception
     */
    public function __construct(
        private readonly array $separators = ["\n\n", "\n", ".", " ", ""],
        int $chunkSize = 4000,
        int $chunkOverlap = 200,
        bool $keepSeparator = true,
        private readonly bool $isSeparatorRegex = false
    ) {
        parent::__construct($chunkSize, $chunkOverlap, $keepSeparator);
    }

    /**
     * @param string $text
     *
     * @return array<int, string>
     */
    public function split(string $text): array
    {
        return $this->doSplit($text, $this->separators);
    }

    /**
     * @param string             $text
     * @param array<int, string> $separators
     *
     * @return array<int, string>
     */
    private function doSplit(string $text, array $separators): array
    {
        $finalChunks = [];
        $separator = "";
        $newSeparators = [];

        foreach ($separators as $i => $nextSeparator) {
            $currentSeparator = $this->sanitizeSeparator($nextSeparator);

            if ($nextSeparator === "") {
                $separator = $nextSeparator;
                break;
            }

            if (preg_match("/$currentSeparator/", $text)) {
                $separator = $nextSeparator;
                $newSeparators = array_slice($separators, $i + 1);
                break;
            }
        }

        $currentSeparator = $this->sanitizeSeparator($separator);
        $splits = $this->splitTextWithRegex($text, $currentSeparator, $this->keepSeparator);

        $goodSplits = [];
        $currentSeparator = $this->keepSeparator ? "" : $separator;

        foreach ($splits as $split) {
            if (strlen($split) < $this->chunkSize) {
                $goodSplits[] = $split;
            } else {
                if (!empty($goodSplits)) {
                    $mergedText = $this->mergeSplit($goodSplits, $currentSeparator);
                    $finalChunks = array_merge($finalChunks, $mergedText);
                    $goodSplits = [];
                }

                if (empty($newSeparators)) {
                    $finalChunks[] = $split;
                } else {
                    $restText = $this->doSplit($split, $newSeparators);
                    $finalChunks = array_merge($finalChunks, $restText);
                }
            }
        }

        if (!empty($goodSplits)) {
            $mergedText = $this->mergeSplit($goodSplits, $currentSeparator);
            $finalChunks = array_merge($finalChunks, $mergedText);
        }

        return $finalChunks;
    }

    /**
     * @param string $separator
     *
     * @return string
     */
    protected function sanitizeSeparator(string $separator): string
    {
        return $this->isSeparatorRegex ? $separator : preg_quote($separator);
    }
}
