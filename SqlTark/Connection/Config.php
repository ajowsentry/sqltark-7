<?php

declare(strict_types=1);

namespace SqlTark\Connection;

use PDO;
use SqlTark\XPDOStatement;

class Config
{
    /**
     * @var string $host
     */
    protected $host = 'localhost';

    /**
     * @var ?int $port
     */
    protected $port = null;
    
    /**
     * @var string $username
     */
    protected $username = '';

    /**
     * @var string $password
     */
    protected $password = '';

    /**
     * @var string $database
     */
    protected $database = '';

    /**
     * @var array<string,mixed> $extras
     */
    protected $extras = [];

    /**
     * @var array<int,mixed> $attributes
     */
    protected $attributes = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_STATEMENT_CLASS => [XPDOStatement::class],
    ];

    /**
     * @param array<string,mixed> $config
     */
    public function __construct(array $config = [])
    {
        if (!empty($config['host'])) {
            $this->host = $config['host'];
            unset($config['host']);
        }

        if (!empty($config['port'])) {
            $this->port = (int) $config['port'];
            unset($config['port']);
        }

        if (!empty($config['username'])) {
            $this->username = $config['username'];
            unset($config['username']);
        }

        if (!empty($config['password'])) {
            $this->password = $config['password'];
            unset($config['password']);
        }

        if (!empty($config['database'])) {
            $this->database = $config['database'];
            unset($config['database']);
        }

        $this->extras = $config;

        if (!empty($config['attributes'])) {
            $this->attributes = array_merge($this->attributes, $config['attributes']);
        }
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return ?int
     */
    public function getPort(): ?int
    {
        return $this->port;
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
     * @return string
     */
    public function getDatabase(): string
    {
        return $this->database;
    }

    /**
     * @return array<int,mixed>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param int $pdoAttribute
     * @return mixed
     */
    public function getAttribute(int $pdoAttribute)
    {
        return $this->attributes[$pdoAttribute] ?? null;
    }

    /**
     * @return int
     */
    public function getFetchMode(): int
    {
        return $this->getAttribute(PDO::ATTR_DEFAULT_FETCH_MODE);
    }

    /**
     * @param string $value
     * @return void
     */
    public function setHost(string $value): void
    {
        $this->host = $value;
    }

    /**
     * @param ?int $value
     * @return void
     */
    public function setPort(?int $value): void
    {
        $this->port = $value;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setUsername(string $value): void
    {
        $this->username = $value;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setPassword(string $value): void
    {
        $this->password = $value;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setDatabase(string $value): void
    {
        $this->database = $value;
    }

    /**
     * @param array<int,mixed> $value
     * @return void
     */
    public function setAttributes(array $value): void
    {
        $this->attributes = $value;
    }

    /**
     * @param int $pdoAttribute
     * @param mixed $value
     * @return void
     */
    public function setAttribute(int $pdoAttribute, $value): void
    {
        $this->attributes[$pdoAttribute] = $value;
    }

    /**
     * @param int $pdoAttribute
     * @return void
     */
    public function unsetAttribute(int $pdoAttribute): void
    {
        unset($this->attributes[$pdoAttribute]);
    }

    /**
     * @param int $value
     * @return void
     */
    public function setFetchMode(int $value): void
    {
        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, $value);
    }

    /**
     * @param string|list<string> $key
     * @param mixed $default
     * @return mixed
     */
    public function getExtras($key, $default = null)
    {
        $keys = (array) $key;

        foreach($keys as $key)
            if(array_key_exists($key, $this->extras))
                return $this->extras[$key];

        return $default;
    }
}