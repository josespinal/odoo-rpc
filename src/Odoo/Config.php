<?php


namespace JoseSpinal\OdooRpc\Odoo;

use JetBrains\PhpStorm\Immutable;

#[Immutable]
class Config
{

    protected array $headers = [];
    protected array $options = [];

    public function __construct(
        protected string $database,
        protected string $host,
        protected string $username,
        protected string $password,
        protected string $protocol = 'json-rpc',
        protected bool $sslVerify = true,
        ?array $headers = null,
        ?array $options = null
    ) {
        if ($headers !== null) {
            $this->headers = $headers;
        }
        if ($options !== null) {
            $this->options = $options;
        }
    }

    /**
     * @return string
     */
    public function getDatabase(): string
    {
        return $this->database;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return boolean
     */
    public function getSslVerify(): bool
    {
        return $this->sslVerify;
    }

    /**
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->protocol;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
