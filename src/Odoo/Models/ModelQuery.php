<?php

namespace JoseSpinal\OdooRpc\Odoo\Models;

use JetBrains\PhpStorm\ExpectedValues;
use JoseSpinal\OdooRpc\Odoo\Collections\OdooCollection;
use JoseSpinal\OdooRpc\Odoo\OdooModel;
use JoseSpinal\OdooRpc\Odoo\Request\RequestBuilder;

class ModelQuery
{
    public function __construct(
        protected OdooModel $model,
        protected RequestBuilder $builder,
    )
    {
    }

    private function newInstance(array|object $values): OdooModel
    {
        if (is_array($values)) {
            $values = (object)$values;
        }
        return $this->model->hydrate($values);
    }


    public function can(string $permission): bool
    {
        return $this->builder->can($permission);
    }

    public function get(): OdooCollection
    {
        $items = array_map(fn($item) => $this->newInstance($item), $this->builder->get());
        return new OdooCollection($items);
    }

    public function first(): ?OdooModel
    {
        $item = $this->builder->first();
        if (null !== $item) {
            return $this->newInstance($item);
        }
        return null;
    }

    public function count(): int
    {
        return $this->builder->count();
    }

    public function delete(): bool
    {
        return $this->builder->delete();
    }

    public function update(array $values): bool
    {
        return $this->builder->update($values);
    }

    public function where(string $field, string $operator, $value)
    {
        $this->builder->where($field, $operator, $value);
        return $this;
    }

    public function orWhere(string $field, string $operator, $value)
    {
        $this->builder->orWhere($field, $operator, $value);
        return $this;
    }

    public function orderBy(string $order, #[ExpectedValues(['asc', 'desc'])] string $direction = 'asc')
    {
        $this->builder->orderBy($order, $direction);
        return $this;
    }

    public function offset(int $offset): static
    {
        $this->builder->offset($offset);
        return $this;
    }

    public function limit(int $limit): static
    {
        $this->builder->limit($limit);
        return $this;
    }

    public function fields(array $fields)
    {
        $this->builder->fields($fields);
        return $this;
    }
}