<?php

namespace Philcross\GetAddress\Tests;

use PHPUnit\Framework\TestCase;
use Philcross\GetAddress\Client;
use Philcross\GetAddress\Responses\Webhook;
use Philcross\GetAddress\Responses\WebhookResponse;

class WebhookTest extends TestCase
{
    use ClientMockerTrait;

    public function test_i_can_make_a_request_to_add_a_new_webhook()
    {
        $http = $this->mockGuzzle(
            'post',
            'https://api.getAddress.io/webhook/first-limit-reached?api-key=78910',
            $this->mockResponse([
                'message' => 'Webhook has been created',
                'id'      => 'abc',
            ]),
            [
                'url' => 'http://phil-cross.co.uk/',
            ]
        );

        $client = new Client('123456', '78910', $http);

        $response = $client->addWebhook('http://phil-cross.co.uk/');

        $this->assertInstanceOf(WebhookResponse::class, $response);
        $this->assertContainsOnlyInstancesOf(Webhook::class, $response->getHooks());
        $this->assertEquals('Webhook has been created', $response->getMessage());
    }

    public function test_i_can_make_a_request_to_delete_an_existing_webhook()
    {
        $http = $this->mockGuzzle(
            'delete',
            'https://api.getAddress.io/webhook/first-limit-reached/abc?api-key=78910',
            $this->mockResponse([
                'message' => 'Webhook has been removed.',
            ])
        );

        $client = new Client('123456', '78910', $http);

        $this->assertInstanceOf(WebhookResponse::class, $client->deleteWebhook('abc'));
    }

    public function test_i_can_retrieve_a_list_of_webhooks()
    {
        $http = $this->mockGuzzle(
            'get',
            'https://api.getAddress.io/webhook/first-limit-reached?api-key=78910',
            $this->mockResponse([
                ['id' => 'abc', 'url' => 'getAddress.io'],
                ['id' => 'def', 'url' => 'phil-cross.co.uk'],
            ])
        );

        $client = new Client('123456', '78910', $http);

        $this->assertInstanceOf(WebhookResponse::class, $client->getWebhooks());
        $this->assertContainsOnlyInstancesOf(Webhook::class, $client->getWebhooks()->getHooks());
    }

    public function test_i_can_retrieve_a_known_webhook()
    {
        $http = $this->mockGuzzle(
            'get',
            'https://api.getAddress.io/webhook/first-limit-reached/abc?api-key=78910',
            $this->mockResponse([
                'id'  => 'abc',
                'url' => 'http://phil-cross.co.uk/',
            ])
        );

        $client = new Client('123456', '78910', $http);

        $this->assertInstanceOf(WebhookResponse::class, $client->getWebhook('abc'));
        $this->assertContainsOnlyInstancesOf(Webhook::class, $client->getWebhook('abc')->getHooks());
    }
}
