<?php

namespace Philcross\GetAddress\Tests\Responses;

use PHPUnit\Framework\TestCase;
use Philcross\GetAddress\Responses\Usage;

class UsageTest extends TestCase
{
    public function test_i_can_use_the_getters_to_retrieve_usage_information()
    {
        $usage = new Usage(10, 20, 25);

        $this->assertEquals(10, $usage->getCount());
        $this->assertEquals(20, $usage->getLimit1());
        $this->assertEquals(25, $usage->getLimit2());

        $this->assertEquals(20, $usage->getLimit(1));
        $this->assertEquals(25, $usage->getLimit(2));

        $this->assertEquals([20, 25], $usage->getLimits());
    }

    public function test_i_can_retrieve_the_number_of_requests_remaining_on_my_account()
    {
        $usage = new Usage(5, 20, 50);

        $this->assertEquals(45, $usage->requestsRemaining());
        $this->assertEquals(15, $usage->requestsRemaining(true));
        $this->assertEquals(15, $usage->requestsRemainingUntilSlowed());
    }

    public function test_i_can_check_if_i_have_exceeded_my_rate_limit()
    {
        $usage = new Usage(0, 20, 25);

        $this->assertFalse($usage->hasExceededLimit());

        $usage = new Usage(100, 20, 25);

        $this->assertTrue($usage->hasExceededLimit());
    }

    public function test_i_can_check_if_my_requests_are_being_slowed()
    {
        $usage = new Usage(0, 20, 25);

        $this->assertFalse($usage->isRestricted());

        $usage = new Usage(22, 20, 25);

        $this->assertTrue($usage->isRestricted());
    }
}
