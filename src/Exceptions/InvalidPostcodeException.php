<?php

namespace Philcross\GetAddress\Exceptions;

use Exception;

class InvalidPostcodeException extends Exception
{
    /**
     * Message
     *
     * @var string
     */
    protected $message = 'The postcode you provided was not a valid format.';
}
