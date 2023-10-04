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

namespace Thojou\LLMDocuments\ValueObjects;

use Thojou\OpenAi\OpenAi;

class DoctranConfig
{
    /**
     * @param OpenAi           $openAi
     * @param non-empty-string $openAiModel
     * @param int              $tokenLimit
     */
    public function __construct(
        private readonly OpenAi $openAi,
        private readonly string $openAiModel,
        private readonly int $tokenLimit
    ) {
    }

    public function getOpenAi(): OpenAi
    {
        return $this->openAi;
    }

    /**
     * @return non-empty-string
     */
    public function getOpenAiModel(): string
    {
        return $this->openAiModel;
    }

    public function getTokenLimit(): int
    {
        return $this->tokenLimit;
    }
}
