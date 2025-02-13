<?php


namespace Obuchmann\OdooJsonRpc\Odoo\Endpoint;


use Obuchmann\OdooJsonRpc\Exceptions\AuthenticationException;
use Obuchmann\OdooJsonRpc\Odoo\Models\Version;

class CommonEndpoint extends AbstractEndpoint
{

    protected function getService(): string
    {
        return 'common';
    }

    public function authenticate(): int
    {
        $client = $this->client;
        $uid = $client
            ->authenticate(
                $this->getConfig()->getDatabase(),
                $this->getConfig()->getUsername(),
                $this->getConfig()->getPassword(),
                ['empty' => 'false']
            );
        if ($uid > 0) {
            return $uid;
        }

        throw new AuthenticationException($client->lastResponse(), "Authentication failed!");
    }


    public function version(): Version
    {
        return Version::hydrate(
            $this->client->call('version', [])
        );
    }
}