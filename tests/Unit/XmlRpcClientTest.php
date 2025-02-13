<?php

namespace JoseSpinal\OdooRpc\Tests\Unit;

use JoseSpinal\OdooRpc\Rpc\XmlRpcClient;
use PHPUnit\Framework\TestCase;

class XmlRpcClientTest extends TestCase
{
    private XmlRpcClient $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new XmlRpcClient('http://localhost:8069', 'common');
    }

    public function testValueConversion()
    {
        $data = [
            'string' => 'test',
            'integer' => 42,
            'float' => 3.14,
            'boolean' => true,
            'array' => ['a', 'b', 'c'],
            'struct' => ['key' => 'value']
        ];

        $method = new \ReflectionMethod(XmlRpcClient::class, 'convertToXmlRpcValue');
        $method->setAccessible(true);

        $xmlRpcValue = $method->invoke($this->client, $data);
        
        $method = new \ReflectionMethod(XmlRpcClient::class, 'convertFromXmlRpcValue');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->client, $xmlRpcValue);

        $this->assertEquals($data, $result);
    }

    public function testNullValueConversion()
    {
        $method = new \ReflectionMethod(XmlRpcClient::class, 'convertToXmlRpcValue');
        $method->setAccessible(true);

        $xmlRpcValue = $method->invoke($this->client, null);
        
        $method = new \ReflectionMethod(XmlRpcClient::class, 'convertFromXmlRpcValue');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->client, $xmlRpcValue);

        $this->assertEquals('', $result);
    }
}
