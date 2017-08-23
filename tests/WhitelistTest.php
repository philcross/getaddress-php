<?php

namespace Philcross\GetAddress\Tests;

use PHPUnit\Framework\TestCase;
use Philcross\GetAddress\Client;
use Philcross\GetAddress\Responses\Ip;
use Philcross\GetAddress\Responses\Domain;
use Philcross\GetAddress\Responses\AbstractWhitelist;
use Philcross\GetAddress\Responses\WhitelistResponse;

class WhitelistTest extends TestCase
{
    use ClientMockerTrait;

    public function test_i_can_make_a_request_to_add_a_new_domain_item_to_the_whitelist()
    {
        $http = $this->mockGuzzle(
            'post',
            'https://api.getAddress.io/security/domain-whitelist?api-key=78910',
            $this->mockResponse([
                'message' => '\'getAddress.io\' has been added to your domain whitelist.',
                'id'      => 'abc',
            ]),
            [
                'name' => 'getAddress.io',
            ]
        );

        $client = new Client('123456', '78910', $http);

        $response = $client->addDomainToWhitelist('getAddress.io');

        $this->assertInstanceOf(WhitelistResponse::class, $response);
        $this->assertContainsOnlyInstancesOf(AbstractWhitelist::class, $response->getItems());
        $this->assertEquals('\'getAddress.io\' has been added to your domain whitelist.', $response->getMessage());
    }

    public function test_i_can_make_a_request_to_add_a_new_ip_address_item_to_the_whitelist()
    {
        $http = $this->mockGuzzle(
            'post',
            'https://api.getAddress.io/security/ip-address-whitelist?api-key=78910',
            $this->mockResponse([
                'message' => '\'your-ip-address\' has been added to your IP address whitelist.',
                'id'      => 'abc',
            ]),
            [
                'value' => '127.0.0.1',
            ]
        );

        $client = new Client('123456', '78910', $http);

        $response = $client->addIpToWhitelist('127.0.0.1');

        $this->assertInstanceOf(WhitelistResponse::class, $response);
        $this->assertContainsOnlyInstancesOf(AbstractWhitelist::class, $response->getItems());
    }

    public function test_i_can_make_a_request_to_delete_an_existing_domain_from_the_whitelist()
    {
        $http = $this->mockGuzzle(
            'delete',
            'https://api.getAddress.io/security/domain-whitelist/abc?api-key=78910',
            $this->mockResponse([
                'message' => '\'getAddress.io\' has been removed from your domain whitelist.',
            ])
        );

        $client = new Client('123456', '78910', $http);

        $this->assertInstanceOf(WhitelistResponse::class, $client->deleteDomainFromWhitelist('abc'));
    }

    public function test_i_can_make_a_request_to_delete_an_existing_ip_address_from_the_whitelist()
    {
        $http = $this->mockGuzzle(
            'delete',
            'https://api.getAddress.io/security/ip-address-whitelist/abc?api-key=78910',
            $this->mockResponse([
                'message' => '\'127.0.0.1\' has been removed from your ip address whitelist.',
            ])
        );

        $client = new Client('123456', '78910', $http);

        $this->assertInstanceOf(WhitelistResponse::class, $client->deleteIpFromWhitelist('abc'));
    }

    public function test_i_can_retrieve_a_list_of_whitelisted_domains()
    {
        $http = $this->mockGuzzle(
            'get',
            'https://api.getAddress.io/security/domain-whitelist?api-key=78910',
            $this->mockResponse([
                ['id' => 'abc', 'name' => 'getAddress.io'],
                ['id' => 'def', 'name' => 'phil-cross.co.uk'],
            ])
        );

        $client = new Client('123456', '78910', $http);

        $this->assertInstanceOf(WhitelistResponse::class, $client->getWhitelistedDomains());
        $this->assertContainsOnlyInstancesOf(Domain::class, $client->getWhitelistedDomains()->getItems());
    }

    public function test_i_can_retrieve_a_list_of_whitelisted_ip_addresses()
    {
        $http = $this->mockGuzzle(
            'get',
            'https://api.getAddress.io/security/ip-address-whitelist?api-key=78910',
            $this->mockResponse([
                ['id' => 'abc', 'value' => '127.0.0.1'],
                ['id' => 'def', 'value' => '8.8.8.8'],
            ])
        );

        $client = new Client('123456', '78910', $http);

        $this->assertInstanceOf(WhitelistResponse::class, $client->getWhitelistedIpAddresses());
        $this->assertContainsOnlyInstancesOf(Ip::class, $client->getWhitelistedIpAddresses()->getItems());
    }

    public function test_i_can_retrieve_a_known_whitelisted_domain()
    {
        $http = $this->mockGuzzle(
            'get',
            'https://api.getAddress.io/security/domain-whitelist/abc?api-key=78910',
            $this->mockResponse([
                'id'   => 'abc',
                'name' => 'getAddress.io',
            ])
        );

        $client = new Client('123456', '78910', $http);

        $this->assertInstanceOf(WhitelistResponse::class, $client->getWhitelistedDomain('abc'));
        $this->assertContainsOnlyInstancesOf(Domain::class, $client->getWhitelistedDomain('abc')->getItems());
    }

    public function test_i_can_retrieve_a_known_whitelisted_ip_address()
    {
        $http = $this->mockGuzzle(
            'get',
            'https://api.getAddress.io/security/ip-address-whitelist/abc?api-key=78910',
            $this->mockResponse([
                'id'    => 'abc',
                'value' => '127.0.0.1',
            ])
        );

        $client = new Client('123456', '78910', $http);

        $this->assertInstanceOf(WhitelistResponse::class, $client->getWhitelistedIpAddress('abc'));
        $this->assertContainsOnlyInstancesOf(Ip::class, $client->getWhitelistedIpAddress('abc')->getItems());
    }
}
