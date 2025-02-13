<?php

namespace JoseSpinal\OdooRpc\Rpc;

use InvalidArgumentException;
use JoseSpinal\OdooRpc\Contracts\RpcClientInterface;

class RpcClientFactory
{
    public const JSON_RPC = 'json-rpc';
    public const XML_RPC = 'xml-rpc';

    public static function create(string $protocol, string $baseUri, string $service = 'object', bool $sslVerify = true): RpcClientInterface
    {
        return match($protocol) {
            self::JSON_RPC => new JsonRpcClient($baseUri, $service, $sslVerify),
            self::XML_RPC => new XmlRpcClient($baseUri, $service, $sslVerify),
            default => throw new InvalidArgumentException("Unsupported RPC protocol: {$protocol}")
        };
    }
}
