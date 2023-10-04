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
use Thojou\LLMDocuments\Transformation\Transformer\DocumentInterrogator;

class DocumentInterrogatorTest extends TestCase
{
    use DocumentTransformerTestTrait;

    public function testTransform(): void
    {
        $openAi = $this->createOpenAiMock([
            'choices' => [
                [
                    'message' => [
                        'function_call' => [
                            'function_name' => 'extract_information',
                            'arguments' => json_encode([
                                'prop1' => 'This is a value of prop1',
                                'prop2' => 'This is a value of prop1',
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
                "name" => 'interrogate',
                "description" => 'Convert a text document into a series of questions and answers',
                "parameters" => [
                    'type' => 'object',
                    'properties' => [
                        "questions_and_answers" => [
                            "type" => "array",
                            "description" => "The list of questions and answers",
                            "items" => [
                                "type" => "object",
                                "properties" => [
                                    'question' => [
                                        'type' => 'string',
                                        'description' => 'The question.'
                                    ],
                                    'answer' => [
                                        'type' => 'string',
                                        'description' => 'The answer.'
                                    ]
                                ],
                                "required" => ['question', 'answer']
                            ]
                        ],
                    ],
                    'required' => ['questions_and_answers']
                ]
            ],
            'function_call' => ['name' => 'interrogate']
        ]);


        $config = $this->createDoctranConfigMock($openAi);
        $document = $this->createDocumentMock();

        $config->method('getTokenLimit')->willReturn(1000);

        $transformer = new DocumentInterrogator($config);
        $document = $transformer->transform($document);
        $this->assertInstanceOf(Document::class, $document);
    }

}
