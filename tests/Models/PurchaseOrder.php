<?php


namespace JoseSpinal\OdooRpc\Tests\Models;

use JoseSpinal\OdooRpc\Attributes\Field;
use JoseSpinal\OdooRpc\Attributes\HasMany;
use JoseSpinal\OdooRpc\Attributes\Key;
use JoseSpinal\OdooRpc\Attributes\Model;
use JoseSpinal\OdooRpc\Odoo\OdooModel;

#[Model('purchase.order')]
class PurchaseOrder extends OdooModel
{
    #[HasMany(PurchaseOrderLine::class, 'order_line')]
    public array $lines;

    #[Field('partner_id'), Key]
    public int $partnerId;

    #[Field('date_order')]
    public \DateTime $orderDate;

    #[Field('date_approve')]
    public ?\DateTime $approveDate;
}