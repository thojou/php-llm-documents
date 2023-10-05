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
use Symfony\Component\Mime\MimeTypes;

class TempFile extends File
{
    /**
     * @throws Exception
     */
    public static function fromContent(string $content, string $mimeType = 'text/plain'): self
    {
        $tmpFile = self::createTempFile($mimeType, $content);

        return new self($tmpFile);
    }

    /**
     * @throws Exception
     */
    private static function createTempFile(string $mimeType, string $content): string
    {
        $extension = self::guessFileExtension($mimeType);
        $tmpFile = self::createTempFilename($extension);

        file_put_contents($tmpFile, $content);

        return $tmpFile;
    }

    private static function createTempFilename(string $extension): string
    {
        return tempnam(sys_get_temp_dir(), 'doctran') . '.' . $extension;
    }

    /**
     * @throws Exception
     */
    private static function guessFileExtension(string $mimeType): string
    {
        $mimeTypes = new MimeTypes();

        $extensions = $mimeTypes->getExtensions($mimeType);

        if(count($extensions) <= 0) {
            throw new Exception('Could not guess file extension for mime type ' . $mimeType);
        }

        return $extensions[0];
    }

    public function close(): void
    {
        parent::close();

        if($this->exists()) {
           unlink($this->filename);
        }
    }
}
