<?php
namespace Puppy\Http;

/**
 * Interface IUri
 * @package Puppy\Http
 */
interface IUri{

    /**
     * Метод возвращаем схему URI
     * @return string
     */
    public function GetScheme(): string;

    /**
     * Метод возвращает новый объект с модифицированной схемой
     * @param string $scheme
     * @return IUri
     */
    public function WithScheme(string $scheme): IUri;

    /**
     * Возвращает строку с именем пользователя и паролем, или пустую строку если не указано имя пользователя и пароль
     * @return string
     */
    public function GetAuthority(): string;

    /**
     * Возвращает имя пользователя или пустую строку
     * @return string
     */
    public function GetUser(): string;

    /**
     * Метод возвращает новый объект с модифицированным пользователем
     * @param string $user
     * @return static
     */
    public function WithUser(string $user): IUri;

    /**
     * Возвращает строку с паролем или пустую строку
     * @return string
     */
    public function GetPass(): string;

    /**
     * Метод возвращает новый объект с модифицированным паролем
     * @param string $pass
     * @return static
     */
    public function WithPass(string $pass): IUri;

    /**
     * Метод возвращает имя хоста
     * @return string
     */
    public function GetHost(): string;

    /**
     * Метод возвращает новый объект с модифицированным хостом
     * @param string $host
     * @return static
     */
    public function WithHost(string $host): IUri;

    /**
     * Метод возвращает порт
     * @return int|null
     */
    public function GetPort(): ?int;

    /**
     * Метод возвращает новый объект с модифицированным портом
     * @param int|null $port
     * @return static
     */
    public function WithPort(?int $port): IUri;

    /**
     * Метод возвращает путь
     * @return string
     */
    public function GetPath(): string;

    /**
     * Метод возвращает новый объект с модифицированным путём
     * @param string $path
     * @return static
     */
    public function WithPath(string $path): IUri;

    /**
     * Метод возвращает строку запроса
     * @return string
     */
    public function GetQuery(): string;

    /**
     * Метод возвращает новый объект с модифицированной строкой запроса
     * @param string $query
     * @return static
     */
    public function WithQuery(string $query): IUri;

    /**
     * Метод возвращает фрагмент
     * @return string
     */
    public function GetFragment(): string;

    /**
     * Метод возвращает новый объект с модифицированным фрагментом
     * @param string $fragment
     * @return static
     */
    public function WithFragment(string $fragment): IUri;

    /**
     * Метод должен возвращать строковое представление uri
     * @return string
     */
    public function __toString();

}