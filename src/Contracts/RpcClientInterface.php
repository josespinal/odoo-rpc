<?php

namespace Obuchmann\OdooJsonRpc\Contracts;

use Psr\Http\Message\ResponseInterface;

interface RpcClientInterface
{
    /**
     * Call a remote method
     *
     * @param string $method The method to call
     * @param array $arguments The arguments to pass
     * @return mixed
     */
    public function call(string $method, array $arguments);

    /**
     * Get the last response from the server
     *
     * @return ResponseInterface|null
     */
    public function getLastResponse(): ?ResponseInterface;

    /**
     * Set the service to use for RPC calls
     *
     * @param string $service
     * @return void
     */
    public function setService(string $service): void;
}
