<?php

namespace JoseSpinal\OdooRpc\Rpc;

use InvalidArgumentException;
use JoseSpinal\OdooRpc\Contracts\RpcClientInterface;

class RpcClientFactory
{
    public const JSON_RPC = 'json-rpc';
    public const XML_RPC = 'xml-rpc';

    public static function create(string $protocol, string $baseUri, string $service, bool $sslVerify = true): RpcClientInterface
    {
        // Normalize protocol name
        $protocol = strtolower(trim($protocol));
        
        if (!in_array($protocol, [self::JSON_RPC, self::XML_RPC])) {
            throw new \InvalidArgumentException(
                "Invalid protocol '$protocol'. Supported protocols are: '" . self::JSON_RPC . "' and '" . self::XML_RPC . "'"
            );
        }
        
        return match($protocol) {
            self::JSON_RPC => new JsonRpcClient($baseUri, $service, $sslVerify),
            self::XML_RPC => new XmlRpcClient($baseUri, $service, $sslVerify),
        };
    }
}
