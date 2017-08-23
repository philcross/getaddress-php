<?php

namespace Philcross\GetAddress\Responses;

class AddressResponse
{
    /**
     * Latitude
     *
     * @var float
     */
    protected $latitude;

    /**
     * Longitude
     *
     * @var float
     */
    protected $longitude;

    /**
     * Addresses
     *
     * @var array
     */
    protected $addresses = [];

    /**
     * Constructor
     *
     * @param float $latitude
     * @param float $longitude
     * @param array $addresses
     */
    public function __construct($latitude, $longitude, array $addresses = [])
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->addresses = $addresses;
    }

    /**
     * Get Latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Get Longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Get Addresses
     *
     * @return array
     */
    public function getAddresses()
    {
        return $this->addresses;
    }
}
