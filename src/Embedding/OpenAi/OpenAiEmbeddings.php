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

namespace Thojou\LLMDocuments\Embedding\OpenAi;

use Exception;
use Thojou\LLMDocuments\Embedding\EmbeddingInterface;
use Thojou\OpenAi\Exception\OpenAiException;
use Thojou\OpenAi\OpenAi;

class OpenAiEmbeddings implements EmbeddingInterface
{
    public function __construct(
        private readonly OpenAi $openAi,
        private readonly string $model
    ) {
    }

    /**
     * @param string $text
     *
     * @return array<int, float>
     * @throws OpenAiException
     */
    public function embedString(string $text): array
    {
        return $this->embed($text)[0];
    }

    /**
     * @param array<int, string> $list
     *
     * @return array<int, array<int, float>>
     * @throws OpenAiException
     */
    public function embedList(array $list): array
    {
        return $this->embed($list);
    }

    /**
     * @param string|array<int, string> $inputs
     *
     * @return array<int, array<int, float>>
     * @throws OpenAiException
     * @throws Exception
     */
    private function embed(string|array $inputs): array
    {
        $result = $this->openAi->embeddings()->embedding([
            'input' => $inputs,
            'model' => $this->model
        ]);

        if(!isset($result['data']) || !is_array($result['data'])) {
            throw new Exception('Unexpected response from OpenAI API');
        }

        return array_map(
            fn (array $data) => $data['embedding'],
            $result['data']
        );
    }
}
