<?php

namespace Philcross\GetAddress\Tests\Responses;

use PHPUnit\Framework\TestCase;
use Philcross\GetAddress\Responses\AddressResponse;

class AddressResponseTest extends TestCase
{
    public function test_i_can_retrieve_all_the_details_from_the_response()
    {
        $response = new AddressResponse(1.0, -0.0, []);

        $this->assertEquals(1.0, $response->getLatitude());
        $this->assertEquals(-0.0, $response->getLongitude());
        $this->assertEquals([], $response->getAddresses());
    }
}
