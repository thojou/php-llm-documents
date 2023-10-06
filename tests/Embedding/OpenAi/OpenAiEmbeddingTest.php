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

namespace Thojou\LLMDocuments\Tests\Embedding\OpenAi;

use PHPUnit\Framework\TestCase;
use Thojou\LLMDocuments\Embedding\OpenAi\OpenAiEmbeddings;
use Thojou\OpenAi\Endpoint\Embeddings;
use Thojou\OpenAi\OpenAi;

class OpenAiEmbeddingTest extends TestCase
{
    public function testEmbedString(): void
    {
        $input = "Hello World!";
        $fakeEmbedding = [15496, 2159, 0];

        $openAi = $this->createMock(OpenAi::class);
        $embeddings = $this->createMock(Embeddings::class);

        $openAi->expects($this->once())
            ->method('embeddings')
            ->willReturn($embeddings);

        $embeddings
            ->expects($this->once())
            ->method('embedding')
            ->with($this->equalTo([
                'input' => $input,
                'model' => 'gpt-3.5-turbo'
            ]))
            ->willReturn([
                'data' => [['embedding' => $fakeEmbedding]]
            ]);

        $embedding = new OpenAiEmbeddings($openAi, 'gpt-3.5-turbo');
        $result = $embedding->embedString($input);

        $this->assertEquals($fakeEmbedding, $result);
    }

    public function testEmbedList(): void
    {
        $inputs = [
            'Hello World',
            'Hello World'
        ];
        $fakeEmbedding = [15496, 2159, 0];

        $openAi = $this->createMock(OpenAi::class);
        $embeddings = $this->createMock(Embeddings::class);

        $openAi->expects($this->once())
            ->method('embeddings')
            ->willReturn($embeddings);

        $embeddings
            ->expects($this->once())
            ->method('embedding')
            ->with($this->equalTo([
                'input' => $inputs,
                'model' => 'gpt-3.5-turbo'
            ]))
            ->willReturn([
                'data' => [['embedding' => $fakeEmbedding], ['embedding' => $fakeEmbedding]]
            ]);

        $embedding = new OpenAiEmbeddings($openAi, 'gpt-3.5-turbo');
        $result = $embedding->embedList($inputs);

        $this->assertEquals([$fakeEmbedding, $fakeEmbedding], $result);
    }

    public function testInvalidResponse(): void
    {
        $this->expectException(\Exception::class);

        $inputs = [
            'Hello World',
            'Hello World'
        ];
        $openAi = $this->createMock(OpenAi::class);
        $embeddings = $this->createMock(Embeddings::class);

        $openAi->expects($this->once())
            ->method('embeddings')
            ->willReturn($embeddings);

        $embeddings
            ->expects($this->once())
            ->method('embedding')
            ->with($this->equalTo([
                'input' => $inputs,
                'model' => 'gpt-3.5-turbo'
            ]))
            ->willReturn([]);

        $embedding = new OpenAiEmbeddings($openAi, 'gpt-3.5-turbo');
        $embedding->embedList($inputs);
    }

}
