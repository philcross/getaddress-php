<?php

namespace Philcross\GetAddress\Exceptions;

use Exception;

class ForbiddenException extends Exception
{
    /**
     * Message
     *
     * @var string
     */
    protected $message = 'Your API key is not valid for this request.';
}
