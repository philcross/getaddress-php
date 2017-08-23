<?php

namespace Philcross\GetAddress\Exceptions;

use Exception;

class TooManyRequestsException extends Exception
{
    /**
     * Message
     *
     * @var string
     */
    protected $message = 'You have made too many requests for this key.';
}
