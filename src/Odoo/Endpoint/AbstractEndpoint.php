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
            $config->getProtocol(),
            $config->getHost(),
            $this->getService(),
            $config->getSslVerify()
        );
    }

    abstract protected function getService(): string;
}
