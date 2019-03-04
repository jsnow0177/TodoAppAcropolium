<?php
namespace Puppy\Http;

/**
 * Class Cookie
 * @package Puppy\Http
 */
class Cookie{

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $value;

    /**
     * @var int
     */
    private $expire;

    /**
     * @var string
     */
    private $path;

    /**
     * @var bool
     */
    private $secure;

    /**
     * @var bool
     */
    private $httpOnly;

    /**
     * Cookie constructor.
     * @param string $name
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param bool $secure
     * @param bool $httpOnly
     */
    public function __construct(string $name, string $value = '', int $expire = 0, string $path = '', bool $secure = false, bool $httpOnly = false)
    {
        $this->name = $name;
        $this->value = $value;
        $this->expire = $expire;
        $this->path = $path;
        $this->secure = $secure;
        $this->httpOnly = $httpOnly;
    }

    /**
     * @return string
     */
    public function GetName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function SetName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function GetValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function SetValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function GetExpire(): int
    {
        return $this->expire;
    }

    /**
     * @param int $expire
     */
    public function SetExpire(int $expire): void
    {
        $this->expire = $expire;
    }

    /**
     * @return string
     */
    public function GetPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function SetPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @return bool
     */
    public function IsSecure(): bool
    {
        return $this->secure;
    }

    /**
     * @param bool $secure
     */
    public function SetSecure(bool $secure): void
    {
        $this->secure = $secure;
    }

    /**
     * @return bool
     */
    public function IsHttpOnly(): bool
    {
        return $this->httpOnly;
    }

    /**
     * @param bool $httpOnly
     */
    public function SetHttpOnly(bool $httpOnly): void
    {
        $this->httpOnly = $httpOnly;
    }

}