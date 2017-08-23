<?php

namespace Philcross\GetAddress\Exceptions;

use Exception;

class PostcodeNotFoundException extends Exception
{
    /**
     * Message
     *
     * @var string
     */
    protected $message = 'The postcode you provided could not be found.';
}
