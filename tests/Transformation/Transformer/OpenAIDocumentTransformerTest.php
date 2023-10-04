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

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Thojou\LLMDocuments\Document\Document;
use Thojou\OpenAi\Exception\OpenAiException;

class OpenAIDocumentTransformerTest extends TestCase
{
    use DocumentTransformerTestTrait;

    public function testWithSingleValueResponse(): void
    {
        $response = [
            'choices' => [
                [
                    'message' => [
                        'function_call' => [
                            'function_name' => 'test_function',
                            'arguments' => json_encode([
                                'summary' => 'This is a summary',
                            ])
                        ]
                    ]
                ]
            ]
        ];

        $openAi = $this->createOpenAiMock($response);
        $config = $this->createDoctranConfigMock($openAi);
        $transformer = $this->createTransformerMock($config);

        $config->method('getTokenLimit')->willReturn(1000);

        $document = $this->createDocumentMock();
        $document = $transformer->transform($document);

        $this->assertInstanceOf(Document::class, $document);
    }

    public function testWithMultiValueResponse(): void
    {
        $response = [
            'choices' => [
                [
                    'message' => [
                        'function_call' => [
                            'function_name' => 'test_function',
                            'arguments' => json_encode([
                                'prop1' => 'This is a value of prop1',
                                'prop2' => 'This is a value of prop2',
                            ])
                        ]
                    ]
                ]
            ]
        ];

        $openAi = $this->createOpenAiMock($response);
        $config = $this->createDoctranConfigMock($openAi);
        $transformer = $this->createTransformerMock($config);

        $config->method('getTokenLimit')->willReturn(1000);

        $document = $this->createDocumentMock();
        $document = $transformer->transform($document);

        $this->assertContains('This is a value of prop1', $document->getExtractedProperties());
        $this->assertContains('This is a value of prop2', $document->getExtractedProperties());
    }

    public function testTokenLimitReached(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Token limit exceeded');

        $openAi = $this->createOpenAiMock([]);
        $config = $this->createDoctranConfigMock($openAi);
        $transformer = $this->createTransformerMock($config);

        $config->method('getTokenLimit')->willReturn(0);

        $document = $this->createDocumentMock();
        $transformer->transform($document);
    }

    public function testInCompleteJson(): void
    {
        $response = [
            'choices' => [
                [
                    'message' => [
                        'function_call' => [
                            'function_name' => 'test_function',
                            'arguments' => '{"summary": "this is a'
                        ]
                    ]
                ]
            ]
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(
            'Could not decode arguments! This is likely due to the completion running out of tokens.'
        );

        $openAi = $this->createOpenAiMock($response);
        $config = $this->createDoctranConfigMock($openAi);
        $transformer = $this->createTransformerMock($config);

        $config->method('getTokenLimit')->willReturn(100);

        $document = $this->createDocumentMock();
        $transformer->transform($document);
    }

    /**
     * @param array<string, mixed> $response
     *
     * @return void
     * @throws Exception
     * @throws OpenAiException
     * @dataProvider provideInvalidResponseData
     */
    public function testInvalidResponse(array $response): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Could not find function call in completion!');

        $openAi = $this->createOpenAiMock($response);
        $config = $this->createDoctranConfigMock($openAi);
        $transformer = $this->createTransformerMock($config);

        $config->method('getTokenLimit')->willReturn(100);

        $document = $this->createDocumentMock();
        $transformer->transform($document);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function provideInvalidResponseData(): array
    {
        return [
            'empty' => [[]],
            'no_choices' => [['choices' => []]],
            'no_message' => [['choices' => [[]]]],
            'no_function_call' => [
                [
                    'choices' => [
                        [
                            'message' => [

                            ]
                        ]
                    ]
                ]
            ],
            'no_arguments' => [
                [
                    'choices' => [
                        [
                            'message' => [
                                'function_call' => [
                                    'function_name' => 'test'
                                ]
                            ]
                        ]
                    ]
                ]
            ],
        ];
    }

}
