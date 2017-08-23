<?php

namespace Philcross\GetAddress\Tests\Responses;

use PHPUnit\Framework\TestCase;
use Philcross\GetAddress\Responses\Ip;
use Philcross\GetAddress\Responses\Domain;
use Philcross\GetAddress\Responses\AbstractWhitelist;

class WhitelistTest extends TestCase
{
    public function test_i_can_use_the_getters_to_retrieve_domain_info()
    {
        $domain = new Domain('abc', 'phil-cross.co.uk');

        $this->assertEquals('abc', $domain->getObjectId());
        $this->assertEquals('phil-cross.co.uk', $domain->getDomain());
    }

    public function test_i_can_use_the_getters_to_retrieve_the_ip_info()
    {
        $ip = new Ip('abc', '127.0.0.1');

        $this->assertEquals('abc', $ip->getObjectId());
        $this->assertEquals('127.0.0.1', $ip->getIp());
    }
}
