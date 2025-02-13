<?php

namespace Obuchmann\OdooJsonRpc\Tests\Feature;

use Obuchmann\OdooJsonRpc\Odoo;
use Obuchmann\OdooJsonRpc\Odoo\Config;
use Obuchmann\OdooJsonRpc\Odoo\Context;
use PHPUnit\Framework\TestCase;

class OdooXmlRpcTest extends TestCase
{
    private Odoo $odoo;

    protected function setUp(): void
    {
        parent::setUp();
        
        $config = new Config(
            database: 'test_db',
            host: 'http://localhost:8069',
            username: 'admin',
            password: 'admin',
            protocol: 'xml-rpc'
        );
        
        $this->odoo = new Odoo($config, new Context());
    }

    public function testVersionCheck()
    {
        $version = $this->odoo->version();
        
        $this->assertNotNull($version);
        $this->assertIsString($version->server_version);
    }

    public function testModelOperations()
    {
        $this->odoo->connect();

        // Test read operation
        $partners = $this->odoo->model('res.partner')
            ->where('is_company', '=', true)
            ->limit(1)
            ->get();
            
        $this->assertIsArray($partners);

        if (count($partners) > 0) {
            $partner = $partners[0];
            
            // Test field access
            $this->assertArrayHasKey('name', $partner);
            $this->assertArrayHasKey('id', $partner);
            
            // Test single record retrieval
            $singlePartner = $this->odoo->model('res.partner')
                ->find($partner['id']);
                
            $this->assertEquals($partner['id'], $singlePartner['id']);
        }
    }

    public function testAccessRights()
    {
        $this->odoo->connect();
        
        $hasAccess = $this->odoo->checkAccessRights('res.partner', 'read');
        $this->assertIsBool($hasAccess);
    }
}
