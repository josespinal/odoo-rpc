<?php

namespace JoseSpinal\OdooRpc\Odoo;

use JoseSpinal\OdooRpc\Attributes\Model;
use JoseSpinal\OdooRpc\Exceptions\ConfigurationException;
use JoseSpinal\OdooRpc\Exceptions\OdooModelException;
use JoseSpinal\OdooRpc\Exceptions\UndefinedPropertyException;
use JoseSpinal\OdooRpc\Odoo;
use JoseSpinal\OdooRpc\Odoo\Collections\OdooCollection;
use JoseSpinal\OdooRpc\Odoo\Mapping\HasFields;

class OdooModel
{
    use HasFields;

    private static Odoo $odoo;
    private static ?string $model = null;

    public static function boot(Odoo $odoo)
    {
        self::$odoo = $odoo;
    }

    public static function listFields(?array $fields = null): object
    {
        return self::$odoo->fieldsGet(static::model(), $fields);
    }

    public static function find(int $id): ?static
    {
        $odooInstance = self::$odoo->find(static::model(), $id, static::fieldNames());
        if(null === $odooInstance){
            return null;
        }
        return static::hydrate($odooInstance);
    }

    public static function read(array $ids): OdooCollection
    {
        $items = array_map(fn($item) => static::hydrate($item), self::$odoo->read(static::model(), $ids, static::fieldNames()));
        return new OdooCollection($items);
    }

    protected static function model()
    {
        $reflectionClass = new \ReflectionClass(static::class);
        $model = $reflectionClass->getAttributes(Model::class)[0] ?? throw new ConfigurationException("Missing Model Attribute");

        return $model->newInstance()->name;
    }

    public static function query()
    {
        //TODO: Lazy evaluate fields only for queries that needs feelds :low
        return new Odoo\Models\ModelQuery(static::newInstance(), self::$odoo->model(static::model())->fields(static::fieldNames()));
    }

    public static function all()
    {
        return static::query()->get();
    }

    /**
     * Get the first record matching the query.
     *
     * @return static|null
     */
    public static function first(): ?static
    {
        return static::query()->first();
    }

    public int $id;

    public function exists()
    {
        return isset($this->id);
    }

    /**
     * @return $this
     */
    public function save(): static
    {
        if ($this->exists()) {
            $updateResponse = self::$odoo->write(static::model(), [$this->id], (array)static::dehydrate($this));
            if (false === $updateResponse) {
                throw new OdooModelException("Failed to update model");
            }
        } else {
            $createResponse = self::$odoo->create(static::model(), (array)static::dehydrate($this));
            if (false === $createResponse) {
                throw new OdooModelException("Failed to create model");
            }
            $this->id = $createResponse;
        }

        return $this;
    }

    public function fill(iterable $properties)
    {
        $reflectionClass = new \ReflectionClass(static::class);

        foreach ($properties as $name => $value) {
            if($reflectionClass->hasProperty($name)){
                $this->{$name} = $value;
            }else {
                throw new UndefinedPropertyException("Property $name not defined");
            }
        }

        return $this;
    }


    public function equals(OdooModel $model)
    {
        $reflectionClass = new \ReflectionClass(static::class);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            if($property->isInitialized($this)){
                if(!$property->isInitialized($model)){
                    return false;
                }
                if($this->{$property->name} !== $model->{$property->name}){
                    return false;
                }
            }else{
                if($property->isInitialized($model)){
                    return false;
                }
            }

        }
        return true;
    }

}