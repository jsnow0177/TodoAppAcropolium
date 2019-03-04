<?php
namespace Puppy\Http;

/**
 * Interface IResponse
 * @package Puppy\Http
 */
interface IResponse extends IMessage{

    /**
     * Отправляет заголовки в браузер.
     * Если тело ответа отправлено, то будет выброшено исключение
     * @throws BodySentException
     */
    public function SendHeaders();

    /**
     * Проверяет отправлены ли заголовки в браузер
     * @return bool
     */
    public function IsHeadersSent(): bool;

    /**
     * Отправляет печеньки в браузер. Если куки уже отправлены, будет выброшено исключение
     * @throws CookiesSentException
     */
    public function SendCookies();

    /**
     * Проверяет отправлены ли куки в браузер
     * @return bool
     */
    public function IsCookiesSent(): bool;

    /**
     * Возвращает тело ответа
     * @return string
     */
    public function GetBody(): string;

    /**
     * Возвращает новый объект с установленным телом ответа
     * @param string $body
     * @return static
     */
    public function WithBody(string $body): IResponse;

    /**
     * Отправляет тело ответа
     */
    public function SendBody();

    /**
     * Проверяет отправлено ли тело ответа в браузер
     * @return bool
     */
    public function IsBodySent(): bool;

    /**
     * Отправляет ответ
     * @throws ResponseSentException
     */
    public function Send();

    /**
     * Проверяет отправлен ли ответ
     * @return bool
     */
    public function IsSent(): bool;

}