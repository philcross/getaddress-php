<?php

namespace Philcross\GetAddress\Exceptions;

use Exception;

class UnknownException extends Exception
{
    /**
     * Message
     *
     * @var string
     */
    protected $message = 'getAddress responded with a %d http status';

    /**
     * {@inheritdoc}
     */
    public function __construct($status)
    {
        $this->message = sprintf($this->message, $status);
    }
}
