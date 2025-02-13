<?php


namespace JoseSpinal\OdooRpc\Odoo\Endpoint;


use JoseSpinal\OdooRpc\JsonRpc\Client;
use JoseSpinal\OdooRpc\Odoo\Config;

class Endpoint
{
    protected string $service;

    private ?Client $client = null;

    /**
     * Endpoint constructor.
     * @param Config $config
     */
    public function __construct(private Config $config)
    {
    }


    public function getClient(bool $fresh = false): Client
    {
        if ($fresh || null == $this->client) {
            $this->client = new Client($this->getConfig()->getHost(), $this->service, $this->getConfig()->getSslVerify());
        }
        return $this->client;
    }

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }




}