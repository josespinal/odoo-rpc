<?php

namespace JoseSpinal\OdooRpc\Odoo\Endpoint;

use JoseSpinal\OdooRpc\Contracts\RpcClientInterface;
use JoseSpinal\OdooRpc\Odoo\Config;
use JoseSpinal\OdooRpc\Rpc\RpcClientFactory;

abstract class AbstractEndpoint
{
    protected RpcClientInterface $client;

    public function __construct(protected Config $config)
    {
        $this->client = RpcClientFactory::create(
            protocol: $config->getProtocol(),
            baseUri: $config->getHost(),
            service: $this->getService(),
            sslVerify: $config->getSslVerify(),
            headers: $config->getHeaders(),
            options: $config->getOptions()
        );
    }

    protected function getConfig(): Config
    {
        return $this->config;
    }

    abstract protected function getService(): string;
}
