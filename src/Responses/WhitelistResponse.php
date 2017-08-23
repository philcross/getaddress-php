<?php

namespace Philcross\GetAddress\Responses;

class WhitelistResponse
{
    /**
     * Message
     *
     * @var string
     */
    protected $message;

    /**
     * Items
     *
     * @var array
     */
    protected $items = [];

    /**
     * Constructor
     *
     * @param string $message
     * @param array $items
     */
    public function __construct($message, array $items = [])
    {
        $this->message = $message;
        $this->items = $items;
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

    /**
     * Get Items
     *
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }
}
