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

class HTMLParser extends UnstructuredParser
{
    /**
     * @param array<array{type: string, text: string, metadata: array{link_texts: array<string>|null}}> $elements
     *
     * @return string
     */
    protected function parseElements(array $elements): string
    {
        $elements = array_filter($elements, function ($item) {
            return $item['type'] !== 'ListItem' && !(
                isset($item['metadata']['link_texts'])
                && $this->clean(join(' ', $item['metadata']['link_texts'])) === $this->clean($item['text'])
            );
        });

        return join(
            "\n\n",
            array_map(fn (array $item) => $this->clean($item['text']), $elements)
        );
    }

    private function clean(string $text): string
    {
        return Cleaner::clean(
            $text,
            [
                'cleanNonAsciiChars',
                'cleanExtraWhitespace',
                'cleanNewLines'
            ]
        );
    }
}
