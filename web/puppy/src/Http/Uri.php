<?php
namespace Puppy\Http;

use Puppy\ObjectValue\TObjectValue;

/**
 * Class Uri
 * @package Puppy\Http
 */
class Uri implements IUri{

    use TObjectValue;

    /**
     * @var string Схема
     */
    private $scheme;

    /**
     * @var string Имя пользователя
     */
    private $user;

    /**
     * @var string Пароль
     */
    private $password;

    /**
     * @var string Имя хоста
     */
    private $host;

    /**
     * @var int|null Порт
     */
    private $port;

    /**
     * @var string Путь
     */
    private $path;

    /**
     * @var string Строка запроса
     */
    private $query;

    /**
     * @var string Фрагмент
     */
    private $fragment;

    /**
     * @param string $uri
     * @return static
     */
    public static function FromUriString(string $uri): IUri
    {
        $parts = parse_url($uri);
        $uri = new static();

        if(isset($parts['scheme']))
            $uri->scheme = $parts['scheme'];

        if(isset($parts['user']))
            $uri->user = $parts['user'];

        if(isset($parts['pass']))
            $uri->password = $parts['pass'];

        if(isset($parts['host']))
            $uri->host = $parts['host'];

        if(isset($parts['port']))
            $uri->port = (int)$parts['port'];

        if(isset($parts['path']))
            $uri->path = preg_replace('/\/{2,}/', '/', '/' . $parts['path'] . '/');;

        if(isset($parts['query']))
            $uri->query = $parts['query'];

        if(isset($parts['fragment']))
            $uri->fragment = $parts['fragment'];

        return $uri;
    }

    /**
     * Uri constructor.
     */
    public function __construct()
    {
        $this->scheme = '';
        $this->user = '';
        $this->password = '';
        $this->host = '';
        $this->port = null;
        $this->path = '/';
        $this->query = '';
        $this->fragment = '';
    }

    /**
     *
     */
    public function __clone()
    {

    }

    /**
     * Метод возвращаем схему URI
     * @return string
     */
    public function GetScheme(): string
    {
        return $this->scheme;
    }

    /**
     * Метод возвращает новый объект с модифицированной схемой
     * @param string $scheme
     * @return static
     */
    public function WithScheme(string $scheme): IUri
    {
        return $this->modifyProperty('scheme', $scheme);
    }

    /**
     * Возвращает строку с именем пользователя и паролем, или пустую строку если не указано имя пользователя и пароль
     * @return string
     */
    public function GetAuthority(): string
    {
        $authority = '';
        if($this->user !== ''){
            $authority .= $this->user;
            if($this->password !== ''){
                $authority .= ':' . $this->password;
            }
        }

        return $authority;
    }

    /**
     * Возвращает имя пользователя или пустую строку
     * @return string
     */
    public function GetUser(): string
    {
        return $this->user;
    }

    /**
     * Метод возвращает новый объект с модифицированным пользователем
     * @param string $user
     * @return static
     */
    public function WithUser(string $user): IUri
    {
        return $this->modifyProperty('user', $user);
    }

    /**
     * Возвращает строку с паролем или пустую строку
     * @return string
     */
    public function GetPass(): string
    {
        return $this->password;
    }

    /**
     * Метод возвращает новый объект с модифицированным паролем
     * @param string $pass
     * @return static
     */
    public function WithPass(string $pass): IUri
    {
        return $this->modifyProperty('password', $pass);
    }

    /**
     * Метод возвращает имя хоста
     * @return string
     */
    public function GetHost(): string
    {
        return $this->host;
    }

    /**
     * Метод возвращает новый объект с модифицированным хостом
     * @param string $host
     * @return static
     */
    public function WithHost(string $host): IUri
    {
        return $this->modifyProperty('host', $host);
    }

    /**
     * Метод возвращает порт
     * @return int|null
     */
    public function GetPort(): ?int
    {
        return $this->port;
    }

    /**
     * Метод возвращает новый объект с модифицированным портом
     * @param int|null $port
     * @return static
     */
    public function WithPort(?int $port): IUri
    {
        return $this->modifyProperty('port', $port);
    }

    /**
     * Метод возвращает путь
     * @return string
     */
    public function GetPath(): string
    {
        return $this->path;
    }

    /**
     * Метод возвращает новый объект с модифицированным путём
     * @param string $path
     * @return static
     */
    public function WithPath(string $path): IUri
    {
        $path = preg_replace('/\/{2,}/', '/', '/' . $path . '/');
        return $this->modifyProperty('path', $path);
    }

    /**
     * Метод возвращает строку запроса
     * @return string
     */
    public function GetQuery(): string
    {
        return $this->query;
    }

    /**
     * Метод возвращает новый объект с модифицированной строкой запроса
     * @param string $query
     * @return static
     */
    public function WithQuery(string $query): IUri
    {
        return $this->modifyProperty('query', $query);
    }

    /**
     * Метод возвращает фрагмент
     * @return string
     */
    public function GetFragment(): string
    {
        return $this->fragment;
    }

    /**
     * Метод возвращает новый объект с модифицированным фрагментом
     * @param string $fragment
     * @return static
     */
    public function WithFragment(string $fragment): IUri
    {
        return $this->modifyProperty('fragment', $fragment);
    }

    /**
     * Метод должен возвращать строковое представление uri
     * @return string
     */
    public function __toString()
    {
        $uri = '';

        if($this->host !== ''){
            if($this->scheme !== '')
                $uri .= $this->scheme . '://';

            if($this->user !== '') {
                $uri .= $this->user;

                if($this->password !== '') {
                    $uri .= ':' . $this->password;
                }

                $uri .= '@';
            }

            $uri .= $this->host;

            if(!is_null($this->port))
                $uri .= ':' . $this->port;
        }

        $uri .= $this->path;

        if($this->query !== '')
            $uri .= '?' . $this->query;

        if($this->fragment !== '')
            $uri .= '#' . $this->fragment;

        return $uri;
    }

}