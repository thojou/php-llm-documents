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

namespace Thojou\LLMDocuments\Tests\Transformation;

use PHPUnit\Framework\TestCase;
use Thojou\LLMDocuments\Document\Document;
use Thojou\LLMDocuments\Tests\Transformation\Transformer\DocumentTransformerTestTrait;
use Thojou\LLMDocuments\Transformation\DocumentTransformationBuilder;

class DocumentTransformerBuilderTest extends TestCase
{
    use DocumentTransformerTestTrait;

    public function testBuilder(): void
    {
        $openAi = $this->createOpenAiMock([
            'choices' => [
                0 => [
                    'message' => [
                        'function_call' => [
                            'function_name' => 'test',
                            'arguments' => json_encode([
                                'test' => 'test'
                            ])
                        ]
                    ]
                ]
            ]
        ]);
        $config = $this->createDoctranConfigMock($openAi);
        $document = $this->createDocumentMock();

        $config->method('getTokenLimit')->willReturn(1000);

        $builder = new DocumentTransformationBuilder($config);
        $document = $builder->refine()->execute($document);
        $this->assertInstanceOf(Document::class, $document);
    }

    public function testChainableTransformationBuilder(): void
    {
        $openAi = $this->createOpenAiMock([]);
        $config = $this->createDoctranConfigMock($openAi);
        $builder = new DocumentTransformationBuilder($config);

        $builder = $builder
            ->translate('de')
            ->summarize(4000)
            ->refine()
            ->interrogate()
            ->extract([]);

        $this->assertInstanceOf(DocumentTransformationBuilder::class, $builder);
    }
}
