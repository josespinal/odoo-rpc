<?php

namespace Obuchmann\OdooJsonRpc\Odoo\Endpoint;

use Obuchmann\OdooJsonRpc\Contracts\RpcClientInterface;
use Obuchmann\OdooJsonRpc\Odoo\Config;
use Obuchmann\OdooJsonRpc\Rpc\RpcClientFactory;

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
