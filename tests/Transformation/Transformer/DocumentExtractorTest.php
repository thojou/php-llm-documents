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
use Thojou\LLMDocuments\Transformation\Transformer\DocumentExtractor;
use Thojou\LLMDocuments\ValueObjects\ExtractProperty;

class DocumentExtractorTest extends TestCase
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
                "name" => 'extract_information',
                "description" => 'Extract structured data from raw text document.',
                "parameters" => [
                    'type' => 'object',
                    'properties' => [
                        "prop1" => [
                            "type" => "string",
                            "description" => "A property."
                        ],
                        "prop2" => [
                            "type" => "string",
                            "description" => "Another property."
                        ]
                    ],
                    'required' => ['prop1', 'prop2']
                ]
            ],
            'function_call' => ['name' => 'extract_information']
        ]);


        $config = $this->createDoctranConfigMock($openAi);
        $document = $this->createDocumentMock();

        $config->method('getTokenLimit')->willReturn(1000);

        $transformer = new DocumentExtractor($config, [
            new ExtractProperty(
                'prop1',
                'string',
                'A property.',
            ),
            new ExtractProperty(
                'prop2',
                'string',
                'Another property.',
            )
        ]);
        $document = $transformer->transform($document);
        $this->assertInstanceOf(Document::class, $document);
    }

}
