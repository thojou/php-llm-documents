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

namespace Thojou\LLMDocuments\Transformation\Transformer;

use Exception;
use Thojou\LLMDocuments\Document\TransformedDocumentInterface;
use Thojou\LLMDocuments\Transformation\DocumentTransformerInterface;
use Thojou\LLMDocuments\ValueObjects\DoctranConfig;
use Thojou\LLMDocuments\ValueObjects\FunctionParameters;
use Thojou\OpenAi\Exception\OpenAiException;
use Yethee\Tiktoken\EncoderProvider;

abstract class OpenAIDocumentTransformer implements DocumentTransformerInterface
{
    protected readonly FunctionParameters $functionParameters;

    public function __construct(
        private readonly DoctranConfig $config
    ) {
        $this->functionParameters = new FunctionParameters();
    }

    /**
     * @throws OpenAiException
     */
    public function transform(TransformedDocumentInterface $document): TransformedDocumentInterface
    {
        $encoder = (new EncoderProvider())->getForModel($this->config->getOpenAiModel());
        $tokenCount = count($encoder->encode($document->getPageContent()));

        if($tokenCount > $this->config->getTokenLimit()) {
            throw new Exception('Token limit exceeded');
        }

        return $this->executeOpenAiCall($document);
    }

    /**
     * @throws OpenAiException
     * @throws Exception
     */
    private function executeOpenAiCall(TransformedDocumentInterface $document): TransformedDocumentInterface
    {
        $completion = $this->config->getOpenAi()->chat()->completion([
            'model' => $this->config->getOpenAiModel(),
            'messages' => [
                ['role' => 'user', 'content' => $document->getPageContent()]
            ],
            'functions' => [
                [
                    "name" => $this->getFunctionName(),
                    "description" => $this->getFunctionDescription(),
                    "parameters" => $this->functionParameters->toArray()
                ]
            ],
            'function_call' => ['name' => $this->getFunctionName()]
        ]);

        $arguments = $this->extractArguments($completion);
        if(!$arguments) {
            throw new Exception('Could not find function call in completion!');
        }

        if(count($arguments) > 1) {
            return $document->mergeExtraProperties($arguments);
        }
        if(count($arguments) === 1) {
            $document->setPageContent((string)array_shift($arguments));
        }

        return $document;
    }

    /**
     * @param array<string, mixed> $completion
     *
     * @return false|array<array-key, scalar>
     * @throws Exception
     */
    private function extractArguments(array $completion): array|false
    {
        if(!isset($completion['choices']) || !is_array($completion['choices']) || count($completion['choices']) === 0) {
            return false;
        }

        $choice = $completion['choices'][0];
        if(!isset($choice['message']) || !is_array($choice['message'])) {
            return false;
        }

        $message = $choice['message'];
        if(!isset($message['function_call']) || !is_array($message['function_call'])) {
            return false;
        }

        $functionCall = $message['function_call'];
        if(!isset($functionCall['arguments']) || !is_string($functionCall['arguments'])) {
            return false;
        }

        $arguments = $functionCall['arguments'];
        $arguments = json_decode($arguments, true);

        if(!is_array($arguments)) {
            throw new Exception('Could not decode arguments! This is likely due to the completion running out of tokens.');
        }

        return $arguments;
    }

    abstract protected function getFunctionName(): string;
    abstract protected function getFunctionDescription(): string;
}
