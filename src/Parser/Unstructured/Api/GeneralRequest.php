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

namespace Thojou\LLMDocuments\Parser\Unstructured\Api;

use Thojou\SimpleApiClient\Contracts\RequestInterface;
use Thojou\SimpleApiClient\Enums\BodyFormat;
use Thojou\SimpleApiClient\Enums\RequestMethod;

class GeneralRequest implements RequestInterface
{
    /**
     * @param resource                $files
     * @param bool|null               $coordinates
     * @param string|null             $encoding
     * @param array<int, string>|null $ocrLanguages
     * @param string|null             $outputFormat
     * @param bool|null               $includePageBreaks
     * @param string|null             $strategy
     * @param bool|null               $pdfInferTableStructure
     * @param array<int, string>|null $skipInferTableTypes
     * @param bool|null               $keepXmlTags
     */
    public function __construct(
        private $files,
        private readonly ?bool $coordinates = null,
        private readonly ?string $encoding = null,
        private readonly ?array $ocrLanguages = null,
        private readonly ?string $outputFormat = null,
        private readonly ?bool $includePageBreaks = null,
        private readonly ?string $strategy = null,
        private readonly ?bool $pdfInferTableStructure = null,
        private readonly ?array $skipInferTableTypes = null,
        private readonly ?bool $keepXmlTags = null,
    ) {
    }

    public function getMethod(): RequestMethod
    {
        return RequestMethod::POST;
    }

    public function getUri(): string
    {
        return 'general/v0/general';
    }

    public function getHeaders(): array
    {
        return [
            'Accept' => 'application/json',
        ];
    }

    public function getBodyFormat(): BodyFormat
    {
        return BodyFormat::MULTIPART;
    }

    public function getBody(): null|array
    {
        $body = [
            'files' => $this->files,
        ];

        if ($this->coordinates !== null) {
            $body['coordinates'] = $this->coordinates;
        }

        if ($this->encoding !== null) {
            $body['encoding'] = $this->encoding;
        }

        if ($this->ocrLanguages !== null) {
            $body['ocr_languages'] = $this->ocrLanguages;
        }

        if ($this->outputFormat !== null) {
            $body['output_format'] = $this->outputFormat;
        }

        if ($this->includePageBreaks !== null) {
            $body['include_page_breaks'] = $this->includePageBreaks;
        }

        if ($this->strategy !== null) {
            $body['strategy'] = $this->strategy;
        }

        if ($this->pdfInferTableStructure !== null) {
            $body['pdf_infer_table_structure'] = $this->pdfInferTableStructure;
        }

        if ($this->skipInferTableTypes !== null) {
            $body['skip_infer_table_types'] = $this->skipInferTableTypes;
        }

        if ($this->keepXmlTags !== null) {
            $body['xml_keep_tags'] = $this->keepXmlTags;
        }

        return $body;
    }

}
