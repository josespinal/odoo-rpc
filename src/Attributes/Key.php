<?php


namespace JoseSpinal\OdooRpc\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Key implements OdooAttribute
{

    public function __construct(
    )
    {
    }
}