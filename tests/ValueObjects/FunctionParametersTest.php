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

namespace Thojou\LLMDocuments\Tests\ValueObjects;

use PHPUnit\Framework\TestCase;
use Thojou\LLMDocuments\ValueObjects\ExtractProperty;
use Thojou\LLMDocuments\ValueObjects\FunctionParameters;

class FunctionParametersTest extends TestCase
{
    public function testFunctionParameters(): void
    {
        $functionParameters = new FunctionParameters();
        $functionParameters->add(new ExtractProperty('requiredParam', 'string', 'test description'));
        $functionParameters->add(new ExtractProperty('optionalPram', 'string', 'test description', false));
        $functionParameters->add(new ExtractProperty('itemParam', 'string', 'test description', false, ['type' => 'object', 'properties' => []]));
        $functionParameters->add(new ExtractProperty('enumParam', 'string', 'test description', false, null, ['enum']));

        $this->assertEquals([
            'type' => 'object',
            'properties' => [
                'requiredParam' => [
                    'type' => 'string',
                    'description' => 'test description',
                ],
                'optionalPram' => [
                    'type' => 'string',
                    'description' => 'test description',
                ],
                'itemParam' => [
                    'type' => 'string',
                    'description' => 'test description',
                    'items' => [
                        'type' => 'object',
                        'properties' => [],
                    ],
                ],
                'enumParam' => [
                    'type' => 'string',
                    'description' => 'test description',
                    'enum' => ['enum'],
                ],
            ],
            'required' => ['requiredParam'],
        ], $functionParameters->toArray());
    }

}
