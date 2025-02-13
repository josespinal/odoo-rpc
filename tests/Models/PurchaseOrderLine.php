<?php


namespace JoseSpinal\OdooRpc\Tests\Models;


use JoseSpinal\OdooRpc\Attributes\Field;
use JoseSpinal\OdooRpc\Attributes\Model;
use JoseSpinal\OdooRpc\Odoo\OdooModel;

#[Model('purchase.order.line')]
class PurchaseOrderLine extends OdooModel
{
    #[Field]
    public string $name;

    #[Field('product_id')]
    public int $productId;

    #[Field('product_qty')]
    public int $productQuantity;

    #[Field('price_unit')]
    public float $priceUnit;
}