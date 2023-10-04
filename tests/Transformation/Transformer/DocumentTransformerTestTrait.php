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
use PHPUnit\Framework\MockObject\MockObject;
use Thojou\LLMDocuments\Document\Document;
use Thojou\LLMDocuments\Document\TransformedDocument;
use Thojou\LLMDocuments\Document\TransformedDocumentInterface;
use Thojou\LLMDocuments\Transformation\Transformer\OpenAIDocumentTransformer;
use Thojou\LLMDocuments\ValueObjects\DoctranConfig;
use Thojou\OpenAi\Endpoint\Chat;
use Thojou\OpenAi\OpenAi;

/**
 * @uses \PHPUnit\Framework\TestCase
 */
trait DocumentTransformerTestTrait
{
    /**
     * @param array<string, mixed> $response
     * @param array<string, mixed>|null $request
     *
     * @throws Exception
     */
    protected function createOpenAiMock(array $response, ?array $request = null): OpenAi&MockObject
    {
        $chat = $this->createMock(Chat::class);

        if($request) {
            $chat->method('completion')->with($this->equalTo($request))->willReturn($response);
        } else {
            $chat->method('completion')->willReturn($response);
        }

        $openAi = $this->createMock(OpenAi::class);
        $openAi->method('chat')->willReturn($chat);

        return $openAi;
    }

    /**
     * @throws Exception
     */
    protected function createDoctranConfigMock(OpenAi|MockObject $openAi): DoctranConfig&MockObject
    {
        $config = $this->createMock(DoctranConfig::class);

        $config->method('getOpenAiModel')->willReturn('gpt-3.5-turbo');
        $config->method('getOpenAi')->willReturn($openAi);

        return $config;
    }

    protected function createTransformerMock(DoctranConfig|MockObject $config): OpenAIDocumentTransformer&MockObject
    {
        $transformer = $this->getMockBuilder(OpenAIDocumentTransformer::class)
            ->setConstructorArgs([$config])
            ->onlyMethods(['getFunctionName', 'getFunctionDescription'])
            ->getMock();
        $transformer->method('getFunctionName')->willReturn('test_function');
        $transformer->method('getFunctionDescription')->willReturn('test_description');

        return $transformer;
    }

    protected function createDocumentMock(): TransformedDocumentInterface
    {
        return new TransformedDocument(
            'This is a test document',
            [],
        );
    }
}
