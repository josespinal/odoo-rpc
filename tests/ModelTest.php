<?php


namespace JoseSpinal\OdooRpc\Tests;


use JoseSpinal\OdooRpc\Odoo;
use JoseSpinal\OdooRpc\Odoo\Casts\CastHandler;
use JoseSpinal\OdooRpc\Odoo\OdooModel;
use JoseSpinal\OdooRpc\Tests\Models\Partner;
use JoseSpinal\OdooRpc\Tests\Models\PurchaseOrder;
use JoseSpinal\OdooRpc\Tests\Models\PurchaseOrderLine;

class ModelTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        OdooModel::boot($this->odoo);
    }

    public function testFields()
    {
        $fields = Partner::listFields();

        $this->assertObjectHasAttribute('name', $fields);
    }

    public function testFind()
    {
        $partner = Partner::find(1);

        $this->assertInstanceOf(Partner::class, $partner);
        $this->assertNotNull($partner->name);
    }

    public function testQuery()
    {
        $partner = Partner::query()
            ->where('name', '=', 'Azure Interior')
            ->first();

        $this->assertInstanceOf(Partner::class, $partner);
        $this->assertEquals('Azure Interior', $partner->name);
    }

    public function testCreate()
    {
        $partner = new Partner();
        $partner->name = 'Tester';
        $partner->save();


        $this->assertNotNull($partner->id);
    }

    public function testReadonlyCreate()
    {
        $partner = new Partner();
        $partner->name = 'Tester';
        $partner->childIds = [1,2,3];
        $partner->save();


        $this->assertNotNull($partner->id);
    }

    public function testUpdate()
    {
        $partner = new Partner();
        $partner->name = 'Tester';
        $partner->save();


        $this->assertNotNull($partner->id);

        $partner->name = "Tester2";
        $partner->save();

        $check = Partner::find($partner->id);

        $this->assertEquals("Tester2", $check->name);
    }

    public function testUpdateNullValue()
    {
        $partner = new Partner();
        $partner->name = 'Tester';
        $partner->email = "tester@example.org";
        $partner->save();


        $this->assertNotNull($partner->id);
        $this->assertNotNull($partner->email);

        $partner->name = "Tester2";
        $partner->email = null;
        $partner->save();

        $check = Partner::find($partner->id);

        $this->assertEquals("Tester2", $check->name);
        $this->assertEquals(null, $check->email);
    }

    public function testSelectColumns()
    {
        $items = Partner::query()->limit(5)
            ->fields(['display_name'])->get();

        $this->assertCount(5, $items);
        $this->assertFalse(isset($items[0]->name));
    }

    public function testOrderBy()
    {
        $items = Partner::query()->limit(5)
            ->orderBy('id', 'desc')
            ->fields(['name'])->get();

        $this->assertIsArray($items);
        $this->assertCount(5, $items);
        $this->assertGreaterThan($items[1]->id, $items[0]->id);
    }


    public function testBelongsTo()
    {

        $parent = new Partner();
        $parent->name = 'Parent';
        $parent->save();

        $child = new Partner();
        $child->parentId = $parent->id;

        $this->assertInstanceOf(Partner::class, $child->parent());
        $this->assertEquals($parent->id, $child->parent()->id);

    }

    public function testHasManyCreate()
    {

        $line = new PurchaseOrderLine();
        $line->name = 'Test';
        $line->productId = 1;
        $line->priceUnit = 10;
        $line->productQuantity = 1;

        $order = new PurchaseOrder();
        $order->partnerId = 1;
        $order->lines = [$line];
        $order->save();


        $this->assertNotNull($order->id);
    }


    public function testCast()
    {
        CastHandler::reset();
        Odoo::registerCast(new Odoo\Casts\DateTimeCast());

        $item = PurchaseOrder::find(1);

        $this->assertNotNull($item->orderDate);
        $this->assertInstanceOf(\DateTime::class, $item->orderDate);

    }

    public function testDateTimezoneCast()
    {
        CastHandler::reset();
        Odoo::registerCast(new Odoo\Casts\DateTimeTimezoneCast(new \DateTimeZone('Europe/Vienna')));

        $item2 = PurchaseOrder::find(1);

        $this->assertNotNull($item2->orderDate);
        $this->assertInstanceOf(\DateTime::class, $item2->orderDate);

        $this->assertEquals("Europe/Vienna", $item2->orderDate->getTimezone()->getName());

    }


    public function testNullableCast()
    {
        CastHandler::reset();
        Odoo::registerCast(new Odoo\Casts\DateTimeCast());

        $item = PurchaseOrder::find(1);

        $this->assertNull($item->approveDate);

    }


    public function testFill()
    {
        $partner = new Partner();
        $partner->fill([
            'name' => 'test'
        ]);

        $this->assertEquals('test', $partner->name);

    }

    public function testEquals()
    {
        $partner = new Partner();
        $partner->name = "test";

        $partner2 = new Partner();
        $partner2->name = "test";

        $partner3 = new Partner();
        $partner3->name = "test";
        $partner3->email = "test";

        $partner4 = new Partner();
        $partner4->name = "test2";

        $partner5 = clone $partner;

        $partner6 = clone $partner;
        $partner6->name = "some";

        $this->assertTrue($partner->equals($partner2));
        $this->assertFalse($partner->equals($partner3));
        $this->assertFalse($partner->equals($partner4));
        $this->assertTrue($partner->equals($partner5));
        $this->assertFalse($partner->equals($partner6));
    }

}