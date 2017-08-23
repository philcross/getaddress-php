<?php

namespace Philcross\GetAddress\Responses;

class Domain extends AbstractWhitelist
{
    /**
     * Get Domain
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->name;
    }
}
