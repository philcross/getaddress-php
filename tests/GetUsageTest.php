<?php

namespace Philcross\GetAddress\Tests;

use DateTime;
use PHPUnit\Framework\TestCase;
use Philcross\GetAddress\Client;
use Philcross\GetAddress\Responses\Usage;

class GetUsageTest extends TestCase
{
    use ClientMockerTrait;

    public function test_i_can_make_a_request_to_retrieve_my_current_usage()
    {
        $http = $this->mockGuzzle(
            'get',
            'https://api.getAddress.io/v2/usage?api-key=78910',
            $this->mockResponse([
                'count' => 10,
                'limit1' => 20,
                'limit2' => 30,
            ])
        );

        $client = new Client('123456', '78910', $http);

        $this->assertInstanceOf(Usage::class, $client->usage());
    }

    public function test_i_can_make_a_request_to_retrieve_my_current_usage_using_a_specified_day()
    {
        $http = $this->mockGuzzle(
            'get',
            'https://api.getAddress.io/v2/usage/01/02/2017?api-key=78910',
            $this->mockResponse([
                'count' => 10,
                'limit1' => 20,
                'limit2' => 30,
            ])
        );

        $client = new Client('123456', '78910', $http);

        $this->assertInstanceOf(Usage::class, $client->usage(01, 02, 2017));
    }

    public function test_i_can_make_a_request_to_retrieve_my_current_usage_using_a_datetime_object()
    {
        $http = $this->mockGuzzle(
            'get',
            'https://api.getAddress.io/v2/usage/01/02/2017?api-key=78910',
            $this->mockResponse([
                'count' => 10,
                'limit1' => 20,
                'limit2' => 30,
            ])
        );

        $client = new Client('123456', '78910', $http);
        $date   = new DateTime('2017-02-01');

        $this->assertInstanceOf(Usage::class, $client->usage($date));
    }
}
