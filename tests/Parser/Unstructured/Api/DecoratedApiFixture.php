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

namespace Thojou\LLMDocuments\Tests\Parser\Unstructured\Api;

use Thojou\LLMDocuments\Parser\Unstructured\Api\UnstructuredAPI;

class DecoratedApiFixture extends UnstructuredAPI
{
    /**
     * @param int                              $statusCode
     * @param array<string, array<int, mixed>> $headers
     * @param string                           $response
     *
     * @return mixed
     */
    public function decoratedOnSuccessResponse(int $statusCode, array $headers, string $response): mixed
    {
        return $this->onSuccessResponse($statusCode, $headers, $response);
    }

    /**
     * @param int                              $statusCode
     * @param array<string, array<int, mixed>> $headers
     * @param string                           $response
     *
     * @return mixed
     */
    public function decoratedOnRedirectResponse(int $statusCode, array $headers, string $response): mixed
    {
        return $this->onRedirectResponse($statusCode, $headers, $response);
    }

    /**
     * @param int                              $statusCode
     * @param array<string, array<int, mixed>> $headers
     * @param string                           $response
     *
     * @return mixed
     */
    public function decoratedOnErrorResponse(int $statusCode, array $headers, string $response): mixed
    {
        return $this->onErrorResponse($statusCode, $headers, $response);
    }
}
