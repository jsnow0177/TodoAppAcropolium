<?php
namespace Puppy\Http;

/**
 * Interface IMessage
 * @package Puppy\Http
 */
interface IMessage{

    /**
     * Возвращает печеньку или null если печеньки нет
     * @param string $name
     * @return Cookie|null
     */
    public function GetCookie(string $name): ?Cookie;

    /**
     * Возвращает список печенек
     * @return Cookie[]
     */
    public function GetCookies(): array;

    /**
     * Возвращает новый объект с добавленной печенькой
     * @param Cookie $cookie
     * @return static
     */
    public function WithCookie(Cookie $cookie): IMessage;

    /**
     * Возвращает новый объект с модифицированным списком печенек
     * @param Cookie[] $cookies
     * @return static
     */
    public function WithCookies(array $cookies): IMessage;

    /**
     * Возвращает все заголовки
     * @return string[][]
     */
    public function GetHeaders(): array;

    /**
     * Проверяет задан ли заголовок
     * @param string $name
     * @return bool
     */
    public function HasHeader(string $name): bool;

    /**
     * Возвращает список всех значений заголовка.
     * Если заголовка нет, то будет возвращён пустой массив.
     * @param string $name
     * @return string[]
     */
    public function GetHeader(string $name): array;

    /**
     * Возвращает значения заголовка разделённые запятыми или пустую строку, если заголовка нет
     * @param string $name
     * @return string
     */
    public function GetHeaderLine(string $name): string;

    /**
     * Возвращает новый объект с изменённым заголовком $name. Если заголовок существует, то его значения будут заменены.
     * Если значение пустая строка или пустой массив, то вызов этого метода эквивалентен вызову метода ::WithoutHeader.
     * @see IMessage::WithoutHeader()
     * @param string $name
     * @param string|string[] $value
     * @return IMessage
     * @throws \InvalidArgumentException
     */
    public function WithHeader(string $name, $value): IMessage;

    /**
     * Возвращает новый объект с обновлёнными значениями заголовка.
     * Есди заголовок существует, то его значения будут дополнены.
     * Если заголовок не существует метод работает так же, как и ::WithHeader.
     * @param string $name
     * @param string|string[] $value
     * @return IMessage
     * @throws \InvalidArgumentException
     */
    public function WithAddedHeader(string $name, $value): IMessage;

    /**
     * Возвращает новый объект с удалённым заголовком.
     * @param string $name
     * @return IMessage
     */
    public function WithoutHeader(string $name): IMessage;

}