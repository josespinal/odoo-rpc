<?php

namespace JoseSpinal\OdooRpc\Odoo\Request;

use JoseSpinal\OdooRpc\Contracts\RpcClientInterface;
use JoseSpinal\OdooRpc\Odoo\Request\Arguments\Options;

abstract class Request
{
    /**
     * Request constructor.
     * @param string $model
     * @param string $method
     */
    public function __construct(
        protected string $model,
        protected string $method
    )
    {
    }

    public abstract function toArray(): array;

    public function execute(
        RpcClientInterface $client,
        string $database,
        int $uid,
        string $password,
        Options $options
    )
    {
        return $client->call('execute_kw', [
            $database,
            $uid,
            $password,
            $this->model,
            $this->method,
            $this->toArray(),
            $options->toArray()
        ]);
    }
}