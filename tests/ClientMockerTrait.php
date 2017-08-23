<?php

namespace Philcross\GetAddress\Tests;

use Mockery as m;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

trait ClientMockerTrait
{
    /**
     * Mock Response
     *
     * @return Psr\Http\Message\ResponseInterface
     */
    private function mockResponse(array $response, $status = 200)
    {
        $mockResponse = m::mock(ResponseInterface::class);

        $mockResponse->shouldReceive('getBody')
            ->once()
            ->andReturn(json_encode($response));

        $mockResponse->shouldReceive('getStatusCode')
            ->andReturn($status);

        return $mockResponse;
    }

    /**
     * Mock Guzzle
     *
     * @return GuzzleHttp\Client
     */
    private function mockGuzzle($method, $url, ResponseInterface $response, array $parameters = [])
    {
        $http = m::mock(ClientInterface::class);

        $http->shouldReceive($method)
            ->once()
            ->with($url, $parameters)
            ->andReturn($response);

        return $http;
    }
}
