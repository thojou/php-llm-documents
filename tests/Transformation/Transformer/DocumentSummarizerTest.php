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

namespace Thojou\LLMDocuments\Tests\Transformation\Transformer;

use PHPUnit\Framework\TestCase;
use Thojou\LLMDocuments\Document\Document;
use Thojou\LLMDocuments\Transformation\Transformer\DocumentSummarizer;

class DocumentSummarizerTest extends TestCase
{
    use DocumentTransformerTestTrait;

    public function testTransform(): void
    {
        $openAi = $this->createOpenAiMock([
            'choices' => [
                [
                    'message' => [
                        'function_call' => [
                            'function_name' => 'summarize',
                            'arguments' => json_encode([
                                'summary' => 'This is a value of prop1',
                            ])
                        ]
                    ]
                ]
            ]
        ], [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => 'This is a test document']
            ],
            'functions' => [
                [
                    "name" => 'summarize',
                    "description" => 'Summarize a document in under 50 tokens.',
                    "parameters" => [
                        'type' => 'object',
                        'properties' => [
                            "summary" => [
                                "type" => "string",
                                "description" => "The summary of the document."
                            ]
                        ],
                        'required' => ['summary']
                    ]
                ]
            ],
            'function_call' => ['name' => 'summarize']
        ]);


        $config = $this->createDoctranConfigMock($openAi);
        $document = $this->createDocumentMock();

        $config->method('getTokenLimit')->willReturn(1000);

        $transformer = new DocumentSummarizer($config, 50);
        $document = $transformer->transform($document);
        $this->assertInstanceOf(Document::class, $document);
    }

}
