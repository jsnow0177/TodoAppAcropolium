<?php
namespace Puppy\Http;

use Puppy\ObjectValue\TObjectValue;

/**
 * Trait TMessage
 * @package Puppy\Http
 */
trait TMessage{

    use TObjectValue;

    /**
     * @var Cookie[]
     */
    private $cookies = [];

    /**
     * @var string[][] Заголовки
     */
    private $headers = [];

    /**
     * Возвращает печеньку или null если печеньки нет
     * @param string $name
     * @return Cookie|null
     */
    public function GetCookie(string $name): ?Cookie
    {
        foreach($this->cookies as $cookie){
            if($cookie->GetName() === $name){
                return $cookie;
            }
        }

        return null;
    }

    /**
     * Возвращает список печенек
     * @return Cookie[]
     */
    public function GetCookies(): array
    {
        return $this->cookies;
    }

    /**
     * @param Cookie $cookie
     * @return static
     */
    public function WithCookie(Cookie $cookie): IMessage
    {
        $new = clone $this;

        foreach($new->cookies as $k => $_cookie){
            if($_cookie->GetName() === $cookie->GetName()){
                $new->cookies[$k] = $cookie;
                return $new;
            }
        }

        $new->cookies[] = $cookie;
        return $new;
    }

    /**
     * Возвращает новый объект с модифицированным списком печенек
     * @param array $cookies
     * @return static
     */
    public function WithCookies(array $cookies): IMessage
    {

        foreach($cookies as $cookie) {
            if (!is_object($cookie) || !is_a($cookie, Cookie::class))
                throw new \InvalidArgumentException("Invalid cookies specified");
        }

        return $this->modifyProperty('cookies', $cookies);
    }

    /**
     * Возвращает все заголовки
     * @return string[][]
     */
    public function GetHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Проверяет задан ли заголовок
     * @param string $name
     * @return bool
     */
    public function HasHeader(string $name): bool
    {
        return (isset($this->headers[$name]) && !empty($this->headers[$name]));
    }

    /**
     * Возвращает список всех значений заголовка.
     * Если заголовка нет, то будет возвращён пустой массив.
     * @param string $name
     * @return string[]
     */
    public function GetHeader(string $name): array
    {
        if(isset($this->headers[$name]))
            return $this->headers[$name];

        return [];
    }

    /**
     * Возвращает значения заголовка разделённые запятыми или пустую строку, если заголовка нет
     * @param string $name
     * @return string
     */
    public function GetHeaderLine(string $name): string
    {
        if(isset($this->headers[$name]))
            return implode(', ', $this->headers[$name]);

        return '';
    }

    /**
     * Возвращает новый объект с изменённым заголовком $name. Если заголовок существует, то его значения будут заменены.
     * Если значение пустая строка или пустой массив, то вызов этого метода эквивалентен вызову метода ::WithoutHeader.
     * @see IMessage::WithoutHeader()
     * @param string $name
     * @param string|string[] $value
     * @return IMessage
     * @throws \InvalidArgumentException
     */
    public function WithHeader(string $name, $value): IMessage
    {
        $value = $this->validateAndTrimHeaderValue($value);

        $new = clone $this;

        if(empty($value) && isset($new->headers[$name])) {
            unset($new->headers[$name]);
        }else{
            $new->headers[$name] = $value;
        }

        return $new;
    }

    /**
     * Возвращает новый объект с обновлёнными значениями заголовка.
     * Есди заголовок существует, то его значения будут дополнены.
     * Если заголовок не существует метод работает так же, как и ::WithHeader.
     * @param string $name
     * @param string|string[] $value
     * @return IMessage
     * @throws \InvalidArgumentException
     */
    public function WithAddedHeader(string $name, $value): IMessage
    {
        $value = $this->validateAndTrimHeaderValue($value);

        $new = clone $this;

        if(!isset($new->headers[$name])) {
            $new->headers[$name] = $value;
        }else{
            foreach($value as $v){
                if(!in_array($v, $new->headers[$name]))
                    $new->headers[$name][] = $v;
            }
        }

        return $new;
    }

    /**
     * Возвращает новый объект с удалённым заголовком.
     * @param string $name
     * @return IMessage
     */
    public function WithoutHeader(string $name): IMessage
    {
        $new = clone $this;

        if(isset($new->headers[$name]))
            unset($new->headers[$name]);

        return $new;
    }

    /**
     * @param string|string[] $value
     * @return array
     * @throws \InvalidArgumentException
     */
    private function validateAndTrimHeaderValue($value){
        if(!is_array($value)){
            if(!is_numeric($value) && !is_string($value)){
                throw new \InvalidArgumentException('Invalid header value specified');
            }

            return [trim((string)$value, " \t")];
        }

        $retValues = [];

        foreach($value as $v){
            if(!is_numeric($v) && !is_string($v))
                throw new \InvalidArgumentException('Invalid header value specified');

            $retValues[] = trim((string)$v, " \t");
        }

        return $retValues;
    }

}