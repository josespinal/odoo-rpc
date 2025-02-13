<?php


namespace JoseSpinal\OdooRpc\Odoo\Endpoint;


use JoseSpinal\OdooRpc\Exceptions\AuthenticationException;
use JoseSpinal\OdooRpc\Odoo\Models\Version;

class CommonEndpoint extends AbstractEndpoint
{

    protected function getService(): string
    {
        return 'common';
    }

    public function authenticate(): int
    {
        $uid = $this->client->call('authenticate', [
            $this->getConfig()->getDatabase(),
            $this->getConfig()->getUsername(),
            $this->getConfig()->getPassword(),
            ['empty' => 'false']
        ]);

        if ($uid && (int)$uid > 0) {
            return (int)$uid;
        }

        throw new AuthenticationException($this->client->getLastResponse(), "Authentication failed!");
    }


    public function version(): Version
    {
        return Version::hydrate(
            $this->client->call('version', [])
        );
    }
}