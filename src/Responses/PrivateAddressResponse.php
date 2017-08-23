<?php

namespace Philcross\GetAddress\Responses;

class PrivateAddressResponse extends AddressResponse
{
    /**
     * Message when the API has performed a POST or DELETE request
     *
     * @var string
     */
    protected $message;

    /**
     * Constructor
     *
     * @param float $latitude
     * @param float $longitude
     * @param array $addresses
     */
    public function __construct($message, array $addresses = [])
    {
        parent::__construct(null, null, $addresses);

        $this->message = $message;
    }

    /**
     * Get Message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
