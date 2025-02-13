<?php

namespace JoseSpinal\OdooRpc\Rpc;

use GuzzleHttp\Exception\GuzzleException;
use JoseSpinal\OdooRpc\Exceptions\OdooException;
use PhpXmlRpc\Client;
use PhpXmlRpc\Request;
use PhpXmlRpc\Value;
use Psr\Http\Message\ResponseInterface;

class XmlRpcClient extends AbstractRpcClient
{
    private Client $xmlRpcClient;

    public function __construct(string $baseUri, string $service = 'object', bool $sslVerify = true)
    {
        parent::__construct($baseUri, $service, $sslVerify);
        $this->xmlRpcClient = new Client($baseUri . '/xmlrpc/2/' . $service);
        $this->xmlRpcClient->setSSLVerifyPeer($sslVerify);
        
        // Configure client for XML-RPC
        // Disable compression as it's not needed for most Odoo servers
        $this->xmlRpcClient->setAcceptedCompression(null);
        $this->xmlRpcClient->setRequestCompression(null);
    }

    public function call(string $method, array $arguments)
    {
        try {
            $params = array_map(function ($arg) {
                return $this->convertToXmlRpcValue($arg);
            }, $arguments);

            $request = new Request($method, $params);
            $response = $this->xmlRpcClient->send($request);

            if ($response->faultCode()) {
                throw new OdooException(
                    null,
                    $response->faultString(),
                    $response->faultCode()
                );
            }

            return $this->convertFromXmlRpcValue($response->value());
        } catch (\Exception $e) {
            if ($e instanceof GuzzleException && $e->getCode() === 405) {
                throw new OdooException(
                    null,
                    "XML-RPC endpoint not available. Please check if the Odoo server accepts XML-RPC connections and the endpoint URL is correct.",
                    405,
                    $e
                );
            }
            throw new OdooException(
                null,
                "XML-RPC call failed: " . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    protected function makeResponse(ResponseInterface $response)
    {
        // Not used in XML-RPC implementation as we're using PhpXmlRpc's response handling
        return null;
    }

    private function convertToXmlRpcValue($value)
    {
        if (is_array($value)) {
            if (array_keys($value) !== range(0, count($value) - 1)) {
                // Associative array (struct)
                $struct = [];
                foreach ($value as $key => $val) {
                    $struct[$key] = $this->convertToXmlRpcValue($val);
                }
                return new Value($struct, 'struct');
            } else {
                // Sequential array
                $array = array_map([$this, 'convertToXmlRpcValue'], $value);
                return new Value($array, 'array');
            }
        } elseif (is_bool($value)) {
            return new Value($value, 'boolean');
        } elseif (is_int($value)) {
            return new Value($value, 'int');
        } elseif (is_float($value)) {
            return new Value($value, 'double');
        } elseif ($value === null) {
            return new Value('', 'string');
        } else {
            return new Value((string)$value, 'string');
        }
    }

    private function convertFromXmlRpcValue($value)
    {
        if ($value instanceof Value) {
            $type = $value->scalartyp();
            $val = $value->scalarval();

            return match($type) {
                'boolean' => (bool)$val,
                'int' => (int)$val,
                'double' => (float)$val,
                'string' => (string)$val,
                'array' => array_map([$this, 'convertFromXmlRpcValue'], $val),
                'struct' => array_map([$this, 'convertFromXmlRpcValue'], (array)$val),
                default => $val,
            };
        }

        return $value;
    }
}
