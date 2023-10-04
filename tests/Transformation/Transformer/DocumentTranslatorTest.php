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
use Thojou\LLMDocuments\Transformation\Transformer\DocumentTranslator;

class DocumentTranslatorTest extends TestCase
{
    use DocumentTransformerTestTrait;

    public function testTransform(): void
    {
        $openAi = $this->createOpenAiMock([
            'choices' => [
                [
                    'message' => [
                        'function_call' => [
                            'function_name' => 'translate',
                            'arguments' => json_encode([
                                'translation' => 'This is a value of prop1',
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
                "name" => 'translate',
                "description" => 'Translate a document into en.',
                "parameters" => [
                    'type' => 'object',
                    'properties' => [
                        "translated_document" => [
                            "type" => "string",
                            "description" => "The document translated into en."
                        ]
                    ],
                    'required' => ['translated_document']
                ]
            ],
            'function_call' => ['name' => 'translate']
        ]);


        $config = $this->createDoctranConfigMock($openAi);
        $document = $this->createDocumentMock();

        $config->method('getTokenLimit')->willReturn(1000);

        $transformer = new DocumentTranslator($config, 'en');
        $document = $transformer->transform($document);
        $this->assertInstanceOf(Document::class, $document);
    }

}
