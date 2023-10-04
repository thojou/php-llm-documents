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
use Thojou\LLMDocuments\Transformation\Transformer\DocumentRefiner;

class DocumentRefinerTest extends TestCase
{
    use DocumentTransformerTestTrait;

    public function testTransformWithoutTopics(): void
    {
        $openAi = $this->createOpenAiMock([
            'choices' => [
                [
                    'message' => [
                        'function_call' => [
                            'function_name' => 'refine',
                            'arguments' => json_encode([
                                'refined_document' => 'This is a value of prop1',
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
                "name" => 'refine',
                "description" => 'Remove all irrelevant information from a document.',
                "parameters" => [
                    'type' => 'object',
                    'properties' => [
                        "refined_document" => [
                            "type" => "string",
                            "description" => "The document with irrelevant information removed."
                        ]
                    ],
                    'required' => ['refined_document']
                ]
            ],
            'function_call' => ['name' => 'refine']
        ]);


        $config = $this->createDoctranConfigMock($openAi);
        $document = $this->createDocumentMock();

        $config->method('getTokenLimit')->willReturn(1000);

        $transformer = new DocumentRefiner($config);
        $document = $transformer->transform($document);
        $this->assertInstanceOf(Document::class, $document);
    }

    public function testTransformWithTopics(): void
    {
        $openAi = $this->createOpenAiMock([
            'choices' => [
                [
                    'message' => [
                        'function_call' => [
                            'function_name' => 'refine',
                            'arguments' => json_encode([
                                'refined_document' => 'This is a value of prop1',
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
                "name" => 'refine',
                "description" => 'Remove all information from a document that is not relevant to the following topics: -software -hardware.',
                "parameters" => [
                    'type' => 'object',
                    'properties' => [
                        "refined_document" => [
                            "type" => "string",
                            "description" => "The document with irrelevant information removed."
                        ]
                    ],
                    'required' => ['refined_document']
                ]
            ],
            'function_call' => ['name' => 'refine']
        ]);


        $config = $this->createDoctranConfigMock($openAi);
        $document = $this->createDocumentMock();

        $config->method('getTokenLimit')->willReturn(1000);

        $transformer = new DocumentRefiner($config, ['software', 'hardware']);
        $document = $transformer->transform($document);
        $this->assertInstanceOf(Document::class, $document);
    }

}
