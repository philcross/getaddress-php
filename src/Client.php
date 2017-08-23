<?php

namespace Philcross\GetAddress;

use DateTime;
use GuzzleHttp\ClientInterface;
use Philcross\GetAddress\Exceptions;
use GuzzleHttp\Client as GuzzleClient;
use Philcross\GetAddress\Responses\PrivateAddress;

class Client
{
    /**
     * API Url
     *
     * @var string
     */
    const API_URL = 'https://api.getAddress.io';

    /**
     * Client for making HTTP calls
     *
     * @var GuzzleHttp\ClientInterface
     */
    protected $http;

    /**
     * Querystring Options
     *
     * @var array
     */
    protected $querystring = [];

    /**
     * Requires Administrative Key to perform operation
     *
     * @var boolean
     */
    protected $requiresAdministrativeKey = false;

    /**
     * API key used for making address lookups
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Administrative key used for account management
     *
     * @var string
     */
    protected $administrativeKey;

    /**
     * Constructor
     *
     * @param string $apiKey
     * @param string $administrativeKey
     * @param GuzzleHttp\ClientInterface|null $http
     *
     * @return void
     */
    public function __construct($apiKey, $administrativeKey, ClientInterface $http = null)
    {
        $this->apiKey = $apiKey;
        $this->administrativeKey = $administrativeKey;

        if (is_null($http)) {
            $http = new GuzzleClient;
        }

        $this->http = $http;
    }

    /**
     * Call an external resource
     *
     * @param string $method Method to get the resource by (GET/POST etc)
     * @param string $url URL to make the request to
     * @param array $parameters Array of parameters to send
     *
     * @return array
     */
    public function call($method, $url, array $parameters = [])
    {
        $this->querystring['api-key'] = $this->requiresAdministrativeKey ? $this->administrativeKey : $this->apiKey;

        $method = strtolower($method);
        $url = sprintf('%s/%s?%s', self::API_URL, $url, http_build_query($this->querystring));

        $response = $this->http->{$method}($url, $parameters);

        if (floor($response->getStatusCode()/100) > 2) {
            $this->throwException($response->getStatusCode());
        }

        return json_decode($response->getBody(), true);
    }

    /**
     * Throw Exception
     *
     * @param integer $statusCode
     *
     * @return void
     */
    protected function throwException($statusCode)
    {
        switch ($statusCode) {
            case 400:
                throw new Exceptions\InvalidPostcodeException;

            case 401:
                throw new Exceptions\ForbiddenException;

            case 404:
                throw new Exceptions\PostcodeNotFoundException;

            case 429:
                throw new Exceptions\TooManyRequestsException;

            case 500:
                throw new Exceptions\ServerException;

            default:
                throw new Exceptions\UnknownException($statusCode);
        }
    }

    /**
     * Find an address or range of addresses by a postcode, and optional number
     *
     * @param string $postcode Postcode to search for
     * @param integer $propertyNumber Property number to narrow down address to
     * @param boolean $sortNumerically Sorts addresses numerically
     *
     * @return Philcross\GetAddress\Responses\AddressResponse
     */
    public function find($postcode, $propertyNumber = null, $sortNumerically = true)
    {
        $this->querystring['sort'] = (int) $sortNumerically;

        $url = sprintf('find/%s', $postcode);

        if (!is_null($propertyNumber)) {
            $url .= sprintf('/%s', $propertyNumber);
        }

        $response =  $this->call('GET', $url);

        return new Responses\AddressResponse(
            $response['latitude'],
            $response['longitude'],
            array_map(function ($address) {
                return new Responses\Address($address);
            }, $response['addresses'])
        );
    }

    /**
     * Get Account Usage
     *
     * @param DateTime|integer|null $day
     * @param integer|null $month
     * @param integer|null $year
     *
     * @return Philcross\GetAddress\Responses\Usage
     */
    public function usage($day = null, $month = null, $year = null)
    {
        $this->requiresAdministrativeKey = true;

        $date = null;

        if (is_numeric($day) && is_numeric($month) && is_numeric($year)) {
            $day = new DateTime(sprintf('%s-%s-%s', $year, $month, $day));
        }

        if ($day instanceof DateTime) {
            $date = sprintf('/%02d/%02d/%02d', $day->format('d'), $day->format('m'), $day->format('Y'));
        }

        if (is_null($day) || is_null($date)) {
            $date = '';
        }

        $response =  $this->call('GET', sprintf('v2/usage%s', $date));

        return new Responses\Usage(
            $response['count'],
            $response['limit1'],
            $response['limit2']
        );
    }

    /**
     * Add Private Address
     *
     * @param string $postcode
     * @param Philcross\GetAddress\Responses\PrivateAddress $address
     *
     * @return Philcross\GetAddress\Responses\PrivateAddressResponse
     */
    public function addPrivateAddress($postcode, PrivateAddress $address)
    {
        $this->requiresAdministrativeKey = true;

        $response = $this->call(
            'POST',
            sprintf('private-address/%s', $postcode),
            $address->toArray(['line1', 'line2', 'line3', 'line4', 'locality', 'townOrCity', 'county'])
        );

        return new Responses\PrivateAddressResponse(
            $response['message'],
            array_map(function ($address) use ($response) {
                return new Responses\PrivateAddress($response['id'], $address);
            }, [$address->toString()])
        );
    }

    /**
     * Delete Private Address
     *
     * @param string $postcode
     * @param integer $id
     *
     * @return Philcross\GetAddress\Responses\PrivateAddressResponse
     */
    public function deletePrivateAddress($postcode, $id)
    {
        $this->requiresAdministrativeKey = true;

        $response = $this->call('DELETE', sprintf('private-address/%s/%s', $postcode, $id));

        return new Responses\PrivateAddressResponse($response['message']);
    }

    /**
     * Find a private address or range of private addresses by a postcode, and optional number
     *
     * @param string $postcode Postcode to search for
     * @param integer $id ID of the private address
     *
     * @return Philcross\GetAddress\Responses\PrivateAddressResponse
     */
    public function getPrivateAddress($postcode, $id = null)
    {
        $this->requiresAdministrativeKey = true;

        $url = sprintf('private-address/%s', $postcode);

        if (!is_null($id)) {
            $url .= sprintf('/%s', $id);
        }

        $response =  $this->call('GET', $url);

        if (!is_null($id)) {
            $response = [$response];
        }

        return new Responses\PrivateAddressResponse(
            null,
            array_map(function ($address) {
                $id = $address['id'];
                unset($address['id']);

                return new Responses\PrivateAddress($id, implode(',', $address));
            }, $response)
        );
    }

    /**
     * Add Domain To Whitelist
     *
     * @param string $domain
     *
     * @return Philcross\GetAddress\Responses\WhitelistResponse
     */
    public function addDomainToWhitelist($domain)
    {
        $this->requiresAdministrativeKey = true;

        $response = $this->call(
            'POST',
            'security/domain-whitelist',
            [
                'name' => $domain,
            ]
        );

        return new Responses\WhitelistResponse(
            $response['message'],
            [new Responses\Domain($response['id'], $domain)]
        );
    }

    /**
     * Add Ip To Whitelist
     *
     * @param string $ipAddress
     *
     * @return Philcross\GetAddress\Responses\WhitelistResponse
     */
    public function addIpToWhitelist($ipAddress)
    {
        $this->requiresAdministrativeKey = true;

        $response = $this->call(
            'POST',
            'security/ip-address-whitelist',
            [
                'value' => $ipAddress,
            ]
        );

        return new Responses\WhitelistResponse(
            $response['message'],
            [new Responses\Ip($response['id'], $ipAddress)]
        );
    }

    /**
     * Delete Domain from Whitelist
     *
     * @param string $id
     *
     * @return Philcross\GetAddress\Responses\WhitelistResponse
     */
    public function deleteDomainFromWhitelist($id)
    {
        $this->requiresAdministrativeKey = true;

        $response = $this->call('DELETE', sprintf('security/domain-whitelist/%s', $id));

        return new Responses\WhitelistResponse($response['message']);
    }

    /**
     * Delete Ip from Whitelist
     *
     * @param string $id
     *
     * @return Philcross\GetAddress\Responses\WhitelistResponse
     */
    public function deleteIpFromWhitelist($id)
    {
        $this->requiresAdministrativeKey = true;

        $response = $this->call('DELETE', sprintf('security/ip-address-whitelist/%s', $id));

        return new Responses\WhitelistResponse($response['message']);
    }

    /**
     * Retrieves a list of whitelisted domains
     *
     * @param string $id ID of the whitelisted domain record to get
     *
     * @return Philcross\GetAddress\Responses\WhitelistResponse
     */
    public function getWhitelistedDomains($id = null)
    {
        $this->requiresAdministrativeKey = true;

        $url = 'security/domain-whitelist';

        if (!is_null($id)) {
            $url .= sprintf('/%s', $id);
        }

        $response =  $this->call('GET', $url);

        if (!is_null($id)) {
            $response = [$response];
        }

        return new Responses\WhitelistResponse(
            null,
            array_map(function ($domain) {
                return new Responses\Domain($domain['id'], $domain['name']);
            }, $response)
        );
    }

    /**
     * Retrieves a list of whitelisted Ip Addresses
     *
     * @param string|null $id
     *
     * @return Philcross\GetAddress\Responses\WhitelistResponse
     */
    public function getWhitelistedIpAddresses($id = null)
    {
        $this->requiresAdministrativeKey = true;

        $url = 'security/ip-address-whitelist';

        if (!is_null($id)) {
            $url .= sprintf('/%s', $id);
        }

        $response =  $this->call('GET', $url);

        if (!is_null($id)) {
            $response = [$response];
        }

        return new Responses\WhitelistResponse(
            null,
            array_map(function ($ip) {
                return new Responses\Ip($ip['id'], $ip['value']);
            }, $response)
        );
    }

    /**
     * Retrieives a known whitelisted domain
     *
     * @return Philcross\GetAddress\Responses\WhitelistResponse
     */
    public function getWhitelistedDomain($id)
    {
        return $this->getWhitelistedDomains($id);
    }

    /**
     * Retrieives a known whitelisted IP Address
     *
     * @return Philcross\GetAddress\Responses\WhitelistResponse
     */
    public function getWhitelistedIpAddress($id)
    {
        return $this->getWhitelistedIpAddresses($id);
    }

    /**
     * Add a webhook
     *
     * @param string $url
     *
     * @return Philcross\GetAddress\Responses\WebhookResponse
     */
    public function addWebhook($url)
    {
        $this->requiresAdministrativeKey = true;

        $response = $this->call(
            'POST',
            'webhook/first-limit-reached',
            [
                'url' => $url,
            ]
        );

        return new Responses\WebhookResponse(
            $response['message'],
            [new Responses\Webhook($response['id'], $url)]
        );
    }

    /**
     * Delete Webhook
     *
     * @param string $id
     *
     * @return Philcross\GetAddress\Responses\WebhookResponse
     */
    public function deleteWebhook($id)
    {
        $this->requiresAdministrativeKey = true;

        $response = $this->call('DELETE', sprintf('webhook/first-limit-reached/%s', $id));

        return new Responses\WebhookResponse($response['message']);
    }

    /**
     * Retrieves a list of webhooks
     *
     * @param string|null $id
     *
     * @return Philcross\GetAddress\Responses\WebhookResponse
     */
    public function getWebhooks($id = null)
    {
        $this->requiresAdministrativeKey = true;

        $url = 'webhook/first-limit-reached';

        if (!is_null($id)) {
            $url .= sprintf('/%s', $id);
        }

        $response =  $this->call('GET', $url);

        if (!is_null($id)) {
            $response = [$response];
        }

        return new Responses\WebhookResponse(
            null,
            array_map(function ($hook) {
                return new Responses\Webhook($hook['id'], $hook['url']);
            }, $response)
        );
    }

    /**
     * Retrieives a known webhook
     *
     * @return Philcross\GetAddress\Responses\WebhookResponse
     */
    public function getWebhook($id)
    {
        return $this->getWebhooks($id);
    }
}
