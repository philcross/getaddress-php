<?php

namespace Philcross\GetAddress\Exceptions;

use Exception;

class ServerException extends Exception
{
    /**
     * Message
     *
     * @var string
     */
    protected $message = 'getAddress.io is currently having issues.';
}
