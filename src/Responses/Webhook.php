<?php

namespace Philcross\GetAddress\Responses;

class Webhook
{
    /**
     * Webhook ID
     *
     * @var string
     */
    protected $id;

    /**
     * Webhook URL
     *
     * @var string
     */
    protected $url;

    /**
     * Constructor
     *
     * @param string $id
     * @param string $url
     *
     * @return void
     */
    public function __construct($id, $url)
    {
        $this->id = $id;
        $this->url = $url;
    }

    /**
     * Get Webhook ID
     *
     * @return string
     */
    public function getWebhookId()
    {
        return $this->id;
    }

    /**
     * Get Webhook Url
     *
     * @return string
     */
    public function getWebhookUrl()
    {
        return $this->url;
    }
}
