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
use Thojou\LLMDocuments\ValueObjects\DoctranConfig;
use Thojou\OpenAi\OpenAi;

class DoctranConfigTest extends TestCase
{
    public function testDoctranConfig(): void
    {
        $openAi = $this->createMock(OpenAi::class);
        $model = 'gpt-3.5-turbo';
        $tokenLimit = 4000;
        $config = new DoctranConfig(
            $openAi,
            $model,
            $tokenLimit
        );

        $this->assertSame($openAi, $config->getOpenAi());
        $this->assertSame($model, $config->getOpenAiModel());
        $this->assertSame($tokenLimit, $config->getTokenLimit());
    }

}
