<?php

namespace Philcross\GetAddress\Responses;

class Ip extends AbstractWhitelist
{
    /**
     * Get Ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->name;
    }
}
