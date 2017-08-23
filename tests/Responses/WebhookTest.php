<?php

namespace Philcross\GetAddress\Tests\Responses;

use PHPUnit\Framework\TestCase;
use Philcross\GetAddress\Responses\Webhook;

class WebhookTest extends TestCase
{
    public function test_i_can_use_the_getters_to_retrieve_webhook_data()
    {
        $webhook = new Webhook('abc', 'http://getAddress.io');

        $this->assertEquals('abc', $webhook->getWebhookId());
        $this->assertEquals('http://getAddress.io', $webhook->getWebhookUrl());
    }
}
