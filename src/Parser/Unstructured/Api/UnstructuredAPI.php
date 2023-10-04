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

namespace Thojou\LLMDocuments\Parser\Unstructured\Api;

use Thojou\SimpleApiClient\AbstractApi;
use Thojou\SimpleApiClient\Exception\ApiException;

class UnstructuredAPI extends AbstractApi
{
    /**
     * @param int                              $statusCode
     * @param array<string, array<int, mixed>> $headers
     * @param string                           $response
     *
     * @return mixed
     */
    protected function onSuccessResponse(int $statusCode, array $headers, string $response): mixed
    {
        return (array) json_decode($response, true);
    }

    /**
     * @param int                              $statusCode
     * @param array<string, array<int, mixed>> $headers
     * @param string                           $response
     *
     * @return mixed
     */
    protected function onRedirectResponse(int $statusCode, array $headers, string $response): mixed
    {
        throw new ApiException('Redirects are not supported');
    }

    /**
     * @param int                              $statusCode
     * @param array<string, array<int, mixed>> $headers
     * @param string                           $response
     *
     * @return mixed
     */
    protected function onErrorResponse(int $statusCode, array $headers, string $response): mixed
    {
        throw new ApiException('Status code ' . $statusCode . ': ' . $response);
    }
}
