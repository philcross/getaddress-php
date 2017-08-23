<?php

namespace Philcross\GetAddress\Tests;

use PHPUnit\Framework\TestCase;
use Philcross\GetAddress\Client;
use Philcross\GetAddress\Responses\PrivateAddress;
use Philcross\GetAddress\Responses\PrivateAddressResponse;

class PrivateAddressTest extends TestCase
{
    use ClientMockerTrait;

    public function test_i_can_make_a_request_to_add_a_new_private_address()
    {
        $http = $this->mockGuzzle(
            'post',
            'https://api.getAddress.io/private-address/AB12BD?api-key=78910',
            $this->mockResponse([
                'message' => '\'postcode/id\' has been added to your private address list.',
                'id'      => 'abc',
            ]),
            [
                'line1'      => 'Sample Line 1',
                'line2'      => 'Sample Line 2',
                'line3'      => 'Sample Line 3',
                'line4'      => 'Sample Line 4',
                'locality'   => 'Sample Locality',
                'townOrCity' => 'Sample City',
                'county'     => 'Sample County',
            ]
        );

        $client = new Client('123456', '78910', $http);

        $address = PrivateAddress::create(
            'Sample Line 1', 'Sample Line 2', 'Sample Line 3', 'Sample Line 4', 'Sample Locality', 'Sample City', 'Sample County'
        );

        $this->assertFalse($address->isSaved());

        $response = $client->addPrivateAddress('AB12BD', $address);

        $this->assertInstanceOf(PrivateAddressResponse::class, $response);
        $this->assertTrue($response->getAddresses()[0]->isSaved());
        $this->assertEquals('\'postcode/id\' has been added to your private address list.', $response->getMessage());
    }

    public function test_i_can_make_a_request_to_delete_an_existing_private_address()
    {
        $http = $this->mockGuzzle(
            'delete',
            'https://api.getAddress.io/private-address/AB12BD/1?api-key=78910',
            $this->mockResponse([
                'message' => 'private address deleted',
            ])
        );

        $client = new Client('123456', '78910', $http);

        $this->assertInstanceOf(PrivateAddressResponse::class, $client->deletePrivateAddress('AB12BD', '1'));
    }

    public function test_i_can_retrieve_a_list_of_private_address()
    {
        $http = $this->mockGuzzle(
            'get',
            'https://api.getAddress.io/private-address/AB12BD?api-key=78910',
            $this->mockResponse([
                [
                    'id'         => 'abc',
                    'line1'      => 'Sample Line 1',
                    'line2'      => 'Sample Line 2',
                    'line3'      => 'Sample Line 3',
                    'line4'      => 'Sample Line 4',
                    'locality'   => 'Sample Locality',
                    'townOrCity' => 'Sample City',
                    'county'     => 'Sample County',
                ]
            ])
        );

        $client = new Client('123456', '78910', $http);

        $this->assertInstanceOf(PrivateAddressResponse::class, $client->getPrivateAddress('AB12BD'));
        $this->assertContainsOnlyInstancesOf(PrivateAddress::class, $client->getPrivateAddress('AB12BD')->getAddresses());
    }

    public function test_i_can_retrieve_a_known_private_address()
    {
        $http = $this->mockGuzzle(
            'get',
            'https://api.getAddress.io/private-address/AB12BD/abc?api-key=78910',
            $this->mockResponse([
                'id'         => 'abc',
                'line1'      => 'Sample Line 1',
                'line2'      => 'Sample Line 2',
                'line3'      => 'Sample Line 3',
                'line4'      => 'Sample Line 4',
                'locality'   => 'Sample Locality',
                'townOrCity' => 'Sample City',
                'county'     => 'Sample County',
            ])
        );

        $client = new Client('123456', '78910', $http);

        $this->assertInstanceOf(PrivateAddressResponse::class, $client->getPrivateAddress('AB12BD', 'abc'));
        $this->assertContainsOnlyInstancesOf(PrivateAddress::class, $client->getPrivateAddress('AB12BD', 'abc')->getAddresses());
    }
}
