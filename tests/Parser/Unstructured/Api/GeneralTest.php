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

namespace Thojou\LLMDocuments\Tests\Parser\Unstructured\Api;

use PHPUnit\Framework\TestCase;
use Thojou\LLMDocuments\Parser\Unstructured\Api\GeneralRequest;

class GeneralTest extends TestCase
{
    public function testGeneralRequest(): void
    {
        /** @var resource $resource */
        $resource = fopen('php://memory', 'r+');

        $request = new GeneralRequest(
            $resource,
            true,
            'utf-8',
            ['eng', 'deu'],
            'json',
            true,
            'auto',
            false,
            ['ignore-params'],
            true
        );

        $this->assertEquals('general/v0/general', $request->getUri());
        $this->assertEquals('POST', $request->getMethod()->value);
        $this->assertEquals('application/json', $request->getHeaders()['Accept']);
        $this->assertEquals('multipart', $request->getBodyFormat()->value);
        $this->assertEquals([
            'files' => $resource,
            'coordinates' => true,
            'encoding' => 'utf-8',
            'ocr_languages' => ['eng', 'deu'],
            'output_format' => 'json',
            'include_page_breaks' => true,
            'strategy' => 'auto',
            'pdf_infer_table_structure' => false,
            'skip_infer_table_types' => ['ignore-params'],
            'xml_keep_tags' => true,
        ], $request->getBody());
    }

}
