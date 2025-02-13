<?php


namespace JoseSpinal\OdooRpc\Attributes;

use Attribute;

#[Attribute]
class HasMany
{

    public function __construct(
        public string $class,
        public string $name
    )
    {
    }
}