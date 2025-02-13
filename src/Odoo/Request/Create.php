<?php


namespace JoseSpinal\OdooRpc\Odoo\Request;


use JoseSpinal\OdooRpc\JsonRpc\Client;

/**
 * Class Create
 *
 * Creates a new model instance
 * @package Obuchmann\OdooJsonRpc\Odoo\Request
 */
class Create extends Request
{

    /**
     * Create constructor.
     * @param string $model
     * @param int[] $values
     */
    public function __construct(
        string $model,
        private array $values,
    )
    {
        parent::__construct($model, 'create');
    }

    public function toArray(): array
    {
        return [
            $this->values
        ];
    }

}