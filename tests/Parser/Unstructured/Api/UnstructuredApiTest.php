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

use PHPUnit\Framework\TestCase;
use Thojou\SimpleApiClient\Contracts\ClientFactoryInterface;
use Thojou\SimpleApiClient\Contracts\ClientInterface;

class UnstructuredApiTest extends TestCase
{
    private DecoratedApiFixture $api;

    protected function setUp(): void
    {
        $clientFactory = $this->createMock(ClientFactoryInterface::class);
        $clientFactory->method('create')->willReturn($this->createMock(ClientInterface::class));

        $this->api = new DecoratedApiFixture($clientFactory);
    }

    public function testSuccess(): void
    {
        $this->assertEquals(
            ['test' => 'test'],
            $this->api->decoratedOnSuccessResponse(200, [], '{"test": "test"}')
        );
    }

    public function testRedirect(): void
    {
        $this->expectExceptionMessage('Redirects are not supported');
        $this->api->decoratedOnRedirectResponse(301, [], 'Redirect');
    }

    public function testError(): void
    {
        $this->expectExceptionMessage('Status code 404: Not Found');
        $this->api->decoratedOnErrorResponse(404, [], 'Not Found');
    }
}
