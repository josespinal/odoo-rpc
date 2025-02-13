<?php


namespace JoseSpinal\OdooRpc\Odoo\Models;


use JetBrains\PhpStorm\Immutable;
use JoseSpinal\OdooRpc\Attributes\Field;
use JoseSpinal\OdooRpc\Odoo\Mapping\HasFields;

class Version
{
    use HasFields;

    // Empty
    public ?int $id = null;

    #[Field('protocol_version')]
    public int $protocolVersion;

    #[Field('server_version')]
    public string $serverVersion;

    #[Field('server_serie')]
    public string $serverSerie;

    #[Field('server_version_info')]
    public array $serverVersionInfo;
}