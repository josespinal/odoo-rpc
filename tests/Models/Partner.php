<?php


namespace JoseSpinal\OdooRpc\Tests\Models;


use JoseSpinal\OdooRpc\Attributes\Field;
use JoseSpinal\OdooRpc\Attributes\Key;
use JoseSpinal\OdooRpc\Attributes\Model;
use JoseSpinal\OdooRpc\Odoo\OdooModel;

#[Model('res.partner')]
class Partner extends OdooModel
{

    #[Field]
    public string $name;

    #[Field('email')]
    public ?string $email;

    #[Field('parent_id'), Key]
    public ?int $parentId;

    #[Field('child_ids')]
    public ?array $childIds;

    public function parent(): Partner
    {
        return Partner::find($this->parentId);
    }

    public function childs()
    {
        return Partner::read($this->childIds);
    }
}