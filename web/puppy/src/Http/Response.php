<?php
namespace Puppy\Http;

use Puppy\ObjectValue\TObjectValue;

/**
 * Class Response
 * @package Puppy\Http
 */
class Response implements IResponse{

    use TObjectValue;
    use TMessage;

    /**
     * @var bool Отправлены ли печеньки
     */
    private $isCookieSent = false;

    /**
     * @var bool Отправлены ли заголовки ответа
     */
    private $isHeadersSent = false;

    /**
     * @var bool Отправлено ли тело ответа
     */
    private $isBodySent = false;

    /**
     * @var string
     */
    private $body = '';

    /**
     * @var bool Отправлен ли ответ
     */
    private $isSent = false;

    /**
     * Response constructor.
     * @param string $body
     */
    public function __construct(string $body = '')
    {
        $this->body = $body;
    }

    /**
     * Отправляет заголовки в браузер.
     * Если тело ответа отправлено, то будет выброшено исключение
     * @throws BodySentException
     */
    public function SendHeaders()
    {
        if($this->isBodySent)
            throw new BodySentException();

        foreach($this->headers as $header => $value){
            header($header . ': ' . implode(',', $value));
        }

        $this->isHeadersSent = true;
    }

    /**
     * Проверяет отправлены ли заголовки в браузер
     * @return bool
     */
    public function IsHeadersSent(): bool
    {
        return $this->isHeadersSent;
    }

    /**
     * Отправляет печеньки в браузер. Если куки уже отправлены, будет выброшено исключение
     * @throws CookiesSentException
     */
    public function SendCookies()
    {
        if($this->isCookieSent)
            throw new CookiesSentException();

        foreach($this->cookies as $cookie)
            setcookie($cookie->GetName(), $cookie->GetValue(), $cookie->GetExpire(), $cookie->GetPath(), '', $cookie->IsSecure(), $cookie->IsHttpOnly());

        $this->isCookieSent = true;
    }

    /**
     * Проверяет отправлены ли куки в браузер
     * @return bool
     */
    public function IsCookiesSent(): bool
    {
        return $this->isCookieSent;
    }

    /**
     * Возвращает тело ответа
     * @return string
     */
    public function GetBody(): string
    {
        return $this->body;
    }

    /**
     * Возвращает новый объект с установленным телом ответа
     * @param string $body
     * @return static
     */
    public function WithBody(string $body): IResponse
    {
        return $this->modifyProperty('body', $body);
    }

    /**
     * Отправляет тело ответа
     */
    public function SendBody()
    {
        echo $this->body;
    }

    /**
     * Проверяет отправлено ли тело ответа в браузер
     * @return bool
     */
    public function IsBodySent(): bool
    {
        return $this->isBodySent;
    }

    /**
     * Отправляет ответ
     * @throws ResponseSentException
     */
    public function Send()
    {
        if($this->isSent)
            throw new ResponseSentException();

        $this->SendHeaders();
        $this->SendCookies();
        $this->SendBody();

        $this->isSent = true;
    }

    /**
     * Проверяет отправлен ли ответ
     * @return bool
     */
    public function IsSent(): bool
    {
        return $this->isSent;
    }

}