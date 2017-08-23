<?php

namespace Philcross\GetAddress\Responses;

abstract class AbstractWhitelist
{
    /**
     * ID of the white listed object
     *
     * @var string
     */
    protected $id;

    /**
     * Name of the whitelisted object
     *
     * @var string
     */
    protected $name;

    /**
     * Constructor
     *
     * @param string $id
     * @param string $name
     *
     * @return void
     */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * Get Object ID
     *
     * @return string
     */
    public function getObjectId()
    {
        return $this->id;
    }
}
