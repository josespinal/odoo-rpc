<?php


namespace JoseSpinal\OdooRpc\Odoo\Request\Arguments;

trait HasOptions
{
    protected Options $options;

    public function option(string $key, $value): static
    {
        $this->options[$key] = $value;
        return $this;
    }
}