<?php

namespace Philcross\GetAddress\Tests\Responses;

use PHPUnit\Framework\TestCase;
use Philcross\GetAddress\Responses\Address;
use Philcross\GetAddress\Responses\PrivateAddress;

class PrivateAddressTest extends TestCase
{
    public function test_i_can_use_getter_methods_to_retrieve_individual_address_elements()
    {
        $address = new PrivateAddress('abc', 'Sample Line 1,Sample Line 2,Sample Line 3,Sample Line 4,Sample Locality,Sample City,Sample County');

        $this->assertInstanceOf(Address::class, $address);

        $this->assertEquals('abc', $address->getAddressId());
    }

    public function test_i_can_create_a_new_private_address_ready_to_save()
    {
        $address = PrivateAddress::create('Sample Line 1', 'Sample Line 2', 'Sample Line 3', 'Sample Line 4', 'Sample Locality', 'Sample City', 'Sample County');

        $this->assertInstanceOf(PrivateAddress::class, $address);
        $this->assertNull($address->getAddressId());
        $this->assertFalse($address->isSaved());
    }
}
