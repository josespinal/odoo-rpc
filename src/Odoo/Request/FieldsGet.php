<?php


namespace JoseSpinal\OdooRpc\Odoo\Request;

use JetBrains\PhpStorm\Immutable;
use JoseSpinal\OdooRpc\JsonRpc\Client;

/**
 * Class FieldsGet
 *
 * Returns model fields description
 * @package Obuchmann\OdooJsonRpc\Odoo\Request
 */
class FieldsGet extends Request
{
    /**
     * FieldsGet constructor.
     * @param string $model
     * @param array|null $fields
     * @param array|null $attributes
     */
    public function __construct(
        string $model,
        private ?array $fields,
        private ?array $attributes
    )
    {
        parent::__construct($model, 'fields_get');
    }

    public function toArray(): array
    {
        return [
            $this->fields,
            $this->attributes
        ];
    }
}