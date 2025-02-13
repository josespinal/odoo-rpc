<?php

namespace JoseSpinal\OdooRpc\Rpc;

use GuzzleHttp\Client as HttpClient;
use JoseSpinal\OdooRpc\Contracts\RpcClientInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractRpcClient implements RpcClientInterface
{
    protected HttpClient $client;
    protected ?ResponseInterface $lastResponse = null;
    protected string $service;

    public function __construct(string $baseUri, string $service = 'object', bool $sslVerify = true)
    {
        $this->service = $service;
        $this->client = new HttpClient([
            'base_uri' => $baseUri,
            'verify' => $sslVerify,
        ]);
    }

    public function getLastResponse(): ?ResponseInterface
    {
        return $this->lastResponse;
    }

    public function setService(string $service): void
    {
        $this->service = $service;
    }

    /**
     * Process the response from the server
     *
     * @param ResponseInterface $response
     * @return mixed
     */
    abstract protected function makeResponse(ResponseInterface $response);
}
