<?php

namespace JoseSpinal\OdooRpc\Rpc;

use GuzzleHttp\Exception\GuzzleException;
use JoseSpinal\OdooRpc\Exceptions\OdooException;
use Psr\Http\Message\ResponseInterface;

class JsonRpcClient extends AbstractRpcClient
{
    public function __construct(string $baseUri, string $service = 'object', bool $sslVerify = true)
    {
        parent::__construct($baseUri, $service, $sslVerify);
        $this->client = new \GuzzleHttp\Client([
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'base_uri' => $baseUri,
            'verify' => $sslVerify,
        ]);
    }

    public function call(string $method, array $arguments)
    {
        try {
            $response = $this->client->request('POST', '/jsonrpc/2/' . $this->service, [
                'json' => [
                    'jsonrpc' => '2.0',
                    'method' => $method,
                    'params' => $arguments,
                    'id' => rand(0, 1000000000)
                ]
            ]);
            $this->lastResponse = $response;

            if ($response->getStatusCode() !== 200) {
                throw new OdooException($response);
            }

            return $this->makeResponse($response);
        } catch (GuzzleException $e) {
            throw new OdooException(null, $e->getMessage(), $e->getCode(), $e);
        }
    }

    protected function makeResponse(ResponseInterface $response)
    {
        $json = json_decode($response->getBody());
        if(isset($json->error)){
            $message = "Odoo Exception";
            if(isset($json->error->message)){
                $message = $json->error->message;
            }
            if(isset($json->error->data) && isset($json->error->data->message)){
                $message .= ': '.$json->error->data->message;
            }
            throw new OdooException($response, $message, $json->error->code ?? null);
        }
        return $json->result ?? $json->id;
    }
}
