<?php

namespace Philcross\GetAddress\Tests;

use PHPUnit\Framework\TestCase;
use Philcross\GetAddress\Client;
use Philcross\GetAddress\Exceptions;

class ClientTest extends TestCase
{
    use ClientMockerTrait;

    public function test_the_client_can_be_instantiated()
    {
        $client = new Client('123456', '78910');

        $this->assertInstanceOf(Client::class, $client);
    }

    public function test_it_correctly_sends_the_api_key_when_making_requests_to_the_api()
    {
        $http = $this->mockGuzzle('get', 'https://api.getAddress.io/?api-key=123456', $this->mockResponse(['worked']));

        $client = new Client('123456', '78910', $http);
        $response = $client->call('GET', '');

        $this->assertEquals(['worked'], $response);
    }

    public function test_its_throws_an_exception_if_a_400_status_is_returned()
    {
        $this->setExpectedException(Exceptions\InvalidPostcodeException::class);

        $http = $this->mockGuzzle('get', 'https://api.getAddress.io/?api-key=123456', $this->mockResponse([], 400));

        $client = new Client('123456', '78910', $http);
        $client->call('GET', '');
    }

    public function test_its_throws_an_exception_if_a_401_status_is_returned()
    {
        $this->setExpectedException(Exceptions\ForbiddenException::class);

        $http = $this->mockGuzzle('get', 'https://api.getAddress.io/?api-key=123456', $this->mockResponse([], 401));

        $client = new Client('123456', '78910', $http);
        $client->call('GET', '');
    }

    public function test_its_throws_an_exception_if_a_404_status_is_returned()
    {
        $this->setExpectedException(Exceptions\PostcodeNotFoundException::class);

        $http = $this->mockGuzzle('get', 'https://api.getAddress.io/?api-key=123456', $this->mockResponse([], 404));

        $client = new Client('123456', '78910', $http);
        $client->call('GET', '');
    }

    public function test_its_throws_an_exception_if_a_429_status_is_returned()
    {
        $this->setExpectedException(Exceptions\TooManyRequestsException::class);

        $http = $this->mockGuzzle('get', 'https://api.getAddress.io/?api-key=123456', $this->mockResponse([], 429));

        $client = new Client('123456', '78910', $http);
        $client->call('GET', '');
    }

    public function test_its_throws_an_exception_if_a_500_status_is_returned()
    {
        $this->setExpectedException(Exceptions\ServerException::class);

        $http = $this->mockGuzzle('get', 'https://api.getAddress.io/?api-key=123456', $this->mockResponse([], 500));

        $client = new Client('123456', '78910', $http);
        $client->call('GET', '');
    }

    public function test_its_throws_a_generic_exception_if_an_unknown_status_is_returned()
    {
        $this->setExpectedException(Exceptions\UnknownException::class);

        $http = $this->mockGuzzle('get', 'https://api.getAddress.io/?api-key=123456', $this->mockResponse([], 418));

        $client = new Client('123456', '78910', $http);
        $client->call('GET', '');
    }
}
