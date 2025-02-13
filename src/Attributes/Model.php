<?php


namespace JoseSpinal\OdooRpc\Attributes;

use Attribute;

#[Attribute]
class Model
{
    public function __construct(
        public string $name
    )
    {
    }
}