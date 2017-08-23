<?php

namespace Philcross\GetAddress\Tests\Responses;

use PHPUnit\Framework\TestCase;
use Philcross\GetAddress\Responses\Address;

class AddressTest extends TestCase
{
    public function test_i_can_use_getter_methods_to_retrieve_individual_address_elements()
    {
        $address = new Address('Sample Line 1,Sample Line 2,Sample Line 3,Sample Line 4,Sample Locality,Sample City,Sample County');

        $this->assertEquals('Sample Line 1', $address->getLine1());
        $this->assertEquals('Sample Line 2', $address->getLine2());
        $this->assertEquals('Sample Line 3', $address->getLine3());
        $this->assertEquals('Sample Line 4', $address->getLine4());

        $this->assertEquals('Sample Line 1', $address->getLine(1));
        $this->assertEquals('Sample Line 2', $address->getLine(2));
        $this->assertEquals('Sample Line 3', $address->getLine(3));
        $this->assertEquals('Sample Line 4', $address->getLine(4));

        $this->assertEquals('Sample Locality', $address->getLocality());
        $this->assertEquals('Sample City', $address->getCity());
        $this->assertEquals('Sample City', $address->getTown());
        $this->assertEquals('Sample County', $address->getCounty());
    }

    public function test_i_can_return_the_address_as_a_simple_string()
    {
        $string = 'Sample Line 1,Sample Line 2,Sample Line 3,Sample Line 4,Sample Locality,Sample City,Sample County';

        $address = new Address($string);

        $this->assertEquals($string, (string) $address);
    }

    public function test_i_can_remove_empty_elements_from_the_address_string()
    {
        $address = new Address('Sample Line 1,,,,,Sample City,');

        $this->assertEquals('Sample Line 1,Sample City', $address->toString(true));
    }

    public function test_i_can_return_the_address_as_a_formatted_array()
    {
        $address = new Address('Sample Line 1,Sample Line 2,Sample Line 3,Sample Line 4,Sample Locality,Sample City,Sample County');

        $this->assertEquals([
            'line_1'    => 'Sample Line 1',
            'line_2'    => 'Sample Line 2',
            'line_3'    => 'Sample Line 3',
            'line_4'    => 'Sample Line 4',
            'locality'  => 'Sample Locality',
            'town_city' => 'Sample City',
            'county'    => 'Sample County',
        ], $address->toArray());
    }

    public function test_i_can_overwrite_the_keys_of_the_array()
    {
        $address = new Address('Sample Line 1,Sample Line 2,Sample Line 3,Sample Line 4,Sample Locality,Sample City,Sample County');

        $this->assertEquals([
            'house_number' => 'Sample Line 1',
            'street'       => 'Sample Line 2',
            'line_3'       => 'Sample Line 3',
            'line_4'       => 'Sample Line 4',
            'locality'     => 'Sample Locality',
            'town_city'    => 'Sample City',
            'county'       => 'Sample County',
        ], $address->toArray(['house_number', 'street']));
    }

    public function test_i_can_compare_two_addresses_to_check_if_they_are_the_same()
    {
        $address1 = new Address('Sample Line 1,Sample Line 2,Sample Line 3,Sample Line 4,Sample Locality,Sample City,Sample County');
        $address2 = new Address('Sample Line 1,Sample Line 2,Sample Line 3,Sample Line 4,Sample Locality,Sample City,Sample County');
        $address3 = new Address('Demo Line 1,Demo Line 2,Demo Line 3,Demo Line 4,Demo Locality,Demo City,Demo County');

        $this->assertTrue($address1->sameAs($address1));
        $this->assertTrue($address1->sameAs($address2));
        $this->assertFalse($address1->sameAs($address3));
    }
}
