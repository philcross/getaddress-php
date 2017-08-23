<?php

namespace Philcross\GetAddress\Responses;

class Address
{
    /**
     * Sort returned addresses numerically
     *
     * @var boolean
     */
    const SORT_NUMERICALLY = true;

    /**
     * Dont perform any specific sort on the returned addresses
     *
     * @var boolean
     */
    const NO_SORT = false;

    /**
     * Address string
     *
     * @var array
     */
    protected $address = [];

    /**
     * Constructor
     *
     * @param string $address
     *
     * @return void
     */
    public function __construct($address)
    {
        $this->address = explode(',', $address);
    }

    /**
     * Get Line 1
     *
     * @return string
     */
    public function getLine1()
    {
        return $this->address[0];
    }

    /**
     * Get Line 2
     *
     * @return string
     */
    public function getLine2()
    {
        return $this->address[1];
    }

    /**
     * Get Line 3
     *
     * @return string
     */
    public function getLine3()
    {
        return $this->address[2];
    }

    /**
     * Get Line 4
     *
     * @return string
     */
    public function getLine4()
    {
        return $this->address[3];
    }

    /**
     * Get Line
     *
     * @param integer $line
     *
     * @return string
     */
    public function getLine($line)
    {
        return $this->address[$line-1];
    }

    /**
     * Get Locality
     *
     * @return string
     */
    public function getLocality()
    {
        return $this->address[4];
    }

    /**
     * Get Town
     *
     * @return string
     */
    public function getTown()
    {
        return $this->address[5];
    }

    /**
     * Get City
     *
     * @return string
     * @see Address:getTown()
     */
    public function getCity()
    {
        return $this->address[5];
    }

    /**
     * County
     *
     * @return string
     */
    public function getCounty()
    {
        return $this->address[6];
    }

    /**
     * Return a formatted array for the address
     *
     * @param array $keys Override default key names
     *
     * @return array
     */
    public function toArray(array $keys = [])
    {
        $keys = array_replace(
            ['line_1', 'line_2', 'line_3', 'line_4', 'locality', 'town_city', 'county'],
            $keys
        );

        return array_combine($keys, $this->address);
    }

    /**
     * Returns a string based on the address
     *
     * @param boolean $removeEmptyElements Prevents strings having conjoining commas
     *
     * @return string
     */
    public function toString($removeEmptyElements = false)
    {
        if (!$removeEmptyElements) {
            return implode(',', $this->address);
        }

        return trim(preg_replace('/,+/', ',', $this->toString()), ',');
    }

    /**
     * Compare two addresss to see if they are equal
     *
     * @param Philcross\GetAddress\Responses\Address $address Address to compare
     *
     * @return boolean
     */
    public function sameAs(Address $address)
    {
        return (bool) !array_diff($this->address, $address->toArray());
    }

    /**
     * Convert the address to a comma seperate string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
