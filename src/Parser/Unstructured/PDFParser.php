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

use Thojou\LLMDocuments\Utils\Cleaner;

class PDFParser extends UnstructuredParser
{
    protected function parseElements(array $elements): string
    {
        $elements = array_filter($elements, function ($item) {
            return !(
                isset($item['metadata']['link_texts'])
                && $this->clean(join(' ', $item['metadata']['link_texts'])) === $this->clean($item['text'])
            );
        });

        $text = '';
        $chunkType = '';
        $chunk = '';

        foreach($elements as $element) {
            if(trim($element['text']) === "") {
                continue;
            }

            if($chunkType === $element['type']) {
                $chunk .= ' ' . $element['text'];
                continue;
            }

            $text .= $this->clean($chunk) . "\n\n";

            $chunkType = $element['type'];
            $chunk = $element["text"];
        }

        $text .= $this->clean($chunk);

        return Cleaner::cleanExtraWhitespace($text);
    }

    protected function getStrategy(): string
    {
        return 'ocr_only';
    }

    private function clean(string $text): string
    {
        return Cleaner::clean(
            $text,
            ['cleanBulletPoints', 'cleanNonAsciiChars', 'cleanNewLines', 'cleanExtraWhitespace']
        );
    }
}
