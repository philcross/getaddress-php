<?php

namespace Philcross\GetAddress\Tests;

use PHPUnit\Framework\TestCase;
use Philcross\GetAddress\Client;
use Philcross\GetAddress\Responses\Address;
use Philcross\GetAddress\Responses\AddressResponse;

class FindAddressTest extends TestCase
{
    use ClientMockerTrait;

    public function test_i_can_make_a_request_to_find_all_addresses_with_only_a_postcode()
    {
        $http = $this->mockGuzzle(
            'get',
            'https://api.getAddress.io/find/AB12BD?sort=1&api-key=123456',
            $this->mockResponse([
                'latitude' => 10.12345678910111,
                'longitude' => -0.12345678910111,
                'addresses' => [
                    'Sample Line 1,Sample Line 2,Sample Line 3,Sample Line 4,Sample Locality,Sample City,Sample County',
                ],
            ])
        );

        $client = new Client('123456', '78910', $http);

        $this->assertInstanceOf(AddressResponse::class, $client->find('AB12BD'));
        $this->assertContainsOnlyInstancesOf(Address::class, $client->find('AB12BD')->getAddresses());
    }

    public function test_i_can_make_a_request_to_find_all_addresses_with_a_postcode_and_property_number()
    {
        $http = $this->mockGuzzle(
            'get',
            'https://api.getAddress.io/find/AB12BD/1?sort=1&api-key=123456',
            $this->mockResponse([
                'latitude' => 10.12345678910111,
                'longitude' => -0.12345678910111,
                'addresses' => [
                    'Sample Line 1,Sample Line 2,Sample Line 3,Sample Line 4,Sample Locality,Sample City,Sample County',
                ],
            ])
        );

        $client = new Client('123456', '78910', $http);

        $this->assertInstanceOf(AddressResponse::class, $client->find('AB12BD', 1));
    }
}
