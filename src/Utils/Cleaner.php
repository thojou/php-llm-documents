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

class Cleaner
{
    public const UNICODE_BULLET_POINTS = [
        '\x{0095}',
        '\x{2022}',
        '\x{2023}',
        '\x{2043}',
        '\x{3164}',
        '\x{204C}',
        '\x{204D}',
        '\x{2219}',
        '\x{25CB}',
        '\x{25CF}',
        '\x{25D8}',
        '\x{25E6}',
        '\x{2619}',
        '\x{2765}',
        '\x{2767}',
        '\x{29BE}',
        '\x{29BF}',
        '\x{002D}',
    ];

    public static function cleanNonAsciiChars(string $string): string
    {
        return (string)iconv('UTF-8', 'ASCII//IGNORE', $string);
    }

    public static function cleanExtraWhitespace(string $string): string
    {
        return trim((string)preg_replace('/\h+/m', ' ', $string));
    }

    public static function cleanNewLines(string $string): string
    {
        $string = preg_replace('/\s*(?<![\n\r])\R(?![\n\r*])\s*/', ' ', $string);
        $string = preg_replace('/\s*(\R{2,})\s*/', '$1', (string)$string);

        return trim((string)$string);

    }

    public static function cleanBulletPoints(string $string): string
    {
        return (string)preg_replace(
            sprintf('/(?:%s)/u', join('|', self::UNICODE_BULLET_POINTS)),
            "*",
            $string
        );
    }

    /**
     * @param string $string
     * @param array<int, string> $steps
     * @return string
     */
    public static function clean(string $string, array $steps = []): string
    {
        return array_reduce(
            $steps,
            fn (string $string, string $step): string => method_exists(
                Cleaner::class,
                $step
            ) ? self::$step($string) : $string,
            $string
        );
    }
}
