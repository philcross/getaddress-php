<?php

namespace Philcross\GetAddress\Responses;

class Usage
{
    /**
     * Number of requests made within the time period
     *
     * @var integer
     */
    protected $count = 0;

    /**
     * Limits imposed on your account of number of lookups allowed
     *
     * @var array
     */
    protected $limits = [0, 0];

    /**
     * Constructor
     *
     * @param integer $count
     * @param integer $limit1
     * @param integer $limit2
     *
     * @return void
     */
    public function __construct($count, $limit1, $limit2)
    {
        $this->count = (int) $count;
        $this->limits = [
            (int) $limit1,
            (int) $limit2,
        ];
    }

    /**
     * Get Count
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Get Limit 1
     *
     * @return integer
     */
    public function getLimit1()
    {
        return $this->limits[0];
    }

    /**
     * Get Limit 2
     *
     * @return integer
     */
    public function getLimit2()
    {
        return $this->limits[1];
    }

    /**
     * Get Limit
     *
     * @param integer $limitNumber
     *
     * @return integer
     */
    public function getLimit($limitNumber)
    {
        return $this->limits[$limitNumber-1];
    }

    /**
     * Get Limits
     *
     * @return array
     */
    public function getLimits()
    {
        return $this->limits;
    }

    /**
     * Requests Remaining
     *
     * @param boolean $untilSlowed Will return requests remaining until calls are slowed by getAddress
     *
     * @return integer
     */
    public function requestsRemaining($untilSlowed = false)
    {
        $limit = $untilSlowed ? $this->limits[0] : $this->limits[1];

        return $limit - $this->count;
    }

    /**
     * Requests Remaining Until Slowed
     *
     * @return integer
     */
    public function requestsRemainingUntilSlowed()
    {
        return $this->requestsRemaining(true);
    }

    /**
     * Has Exceeded Limit
     *
     * @return boolean
     */
    public function hasExceededLimit()
    {
        return $this->count > $this->limits[1];
    }

    /**
     * Returns whether the initial limit has been reached and whether subsequent
     * requests have been slowed down by getAddress
     *
     * @return boolean
     */
    public function isRestricted()
    {
        return $this->count >= $this->limits[0];
    }
}
