<?php

namespace JoseSpinal\OdooRpc\Rpc;

use PhpXmlRpc\Client as BaseClient;
use PhpXmlRpc\Request;
use PhpXmlRpc\Response;

class CustomXmlRpcClient extends BaseClient
{
    /**
     * Override the send method to implement your custom transport
     */
    public function send($req, $timeout = 0, $method = '')
    {
        // Your custom transport logic here
        // You can:
        // 1. Use Guzzle or any other HTTP client
        // 2. Implement custom SSL/TLS handling
        // 3. Add custom headers or authentication
        // 4. Implement retry logic
        // 5. Add logging or monitoring

        // Example using parent's send method but with custom preparation:
        var_dump($req);
        $req->setOption('laportena', 'ke8l5qeobg');
        
        return parent::send($req, $timeout, $method);
    }
}