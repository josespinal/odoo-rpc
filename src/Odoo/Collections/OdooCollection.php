<?php

namespace JoseSpinal\OdooRpc\Odoo\Collections;

use Illuminate\Support\Collection;
use JoseSpinal\OdooRpc\Odoo\OdooModel;

class OdooCollection extends Collection
{
    /**
     * Create a new collection instance.
     *
     * @param  mixed  $items
     * @return void
     */
    public function __construct($items = [])
    {
        parent::__construct($items);
    }

    /**
     * Create a new collection instance if the value isn't one already.
     *
     * @param  mixed  $items
     * @return static
     */
    public static function make($items = [])
    {
        return new static($items);
    }

    /**
     * Get the first item from the collection.
     *
     * @param  callable|null  $callback
     * @param  mixed  $default
     * @return mixed
     */
    public function first(callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            return empty($this->items) ? value($default) : reset($this->items);
        }

        return parent::first($callback, $default);
    }

    /**
     * Get the IDs of all models in the collection.
     *
     * @return static
     */
    public function ids()
    {
        return $this->pluck('id');
    }

    /**
     * Convert the collection to a plain array of Odoo records.
     *
     * @return array
     */
    public function toOdooArray()
    {
        return array_map(function ($value) {
            return $value instanceof OdooModel ? $value->toArray() : $value;
        }, $this->items);
    }
}
