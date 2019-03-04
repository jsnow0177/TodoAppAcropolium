<?php
namespace Puppy\Http;

use Puppy\ObjectValue\TObjectValue;

/**
 * Class Request
 * @package Puppy\Http
 */
class Request implements IRequest{

    use TObjectValue;
    use TMessage;

    /**
     * @var string HTTP-метод
     */
    private $method;

    /**
     * @var array Параметры запроса
     */
    private $queryParams;

    /**
     * @var array POST-параметры
     */
    private $postParams;

    /**
     * @var array Загруженные файлы
     */
    private $uploadedFiles;

    /**
     * @var array Атрибуты запроса
     */
    private $attributes;

    /**
     * @var Uri
     */
    private $uri;

    /**
     * @param array $attributes
     * @return IRequest
     */
    public static function FromGlobal(array $attributes = []): IRequest
    {
        $request = (new static())
            ->WithMethod($_SERVER['REQUEST_METHOD'])
            ->WithUri(Uri::FromUriString($_SERVER['REQUEST_URI']))
            ->WithPostParams($_POST)
            ->WithUploadedFiles($_FILES)
            ->WithAttributes($attributes);

        return $request;
    }

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->method = 'GET';
        $this->queryParams = [];
        $this->postParams = [];
        $this->uploadedFiles = [];
        $this->attributes = [];
        $this->uri = new Uri();
    }

    /**
     *
     */
    public function __clone()
    {
        $newUri = clone $this->uri;
        $this->uri = $newUri;
    }

    /**
     * Возвращает HTTP-метод
     * @return string
     */
    public function GetMethod(): string
    {
        return $this->method;
    }

    /**
     * Возвращает новый объект с модифицированным методом
     * @param string $method
     * @return static
     */
    public function WithMethod(string $method): IRequest
    {
        return $this->modifyProperty('method', $method);
    }

    /**
     * Возвращает массив параметров запроса
     * @return array
     */
    public function GetQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * Возвращает новый объект с изменёнными параметрами запроса.
     * При модификации параметров запроса изменяется также и объект uri
     * @param array $queryParams
     * @return static
     */
    public function WithQueryParams(array $queryParams): IRequest
    {
        $new = $this->modifyProperty('queryParams', $queryParams);

        $queryStr = http_build_query($queryParams, null, null, PHP_QUERY_RFC3986);
        $new->uri = $new->uri->WithQuery($queryStr);

        return $new;
    }

    /**
     * Возвращает массив POST-параметров
     * @return array
     */
    public function GetPostParams(): array
    {
        return $this->postParams;
    }

    /**
     * Возвращает новый объект с изменёнными POST-параметрами
     * @param array $postParams
     * @return static
     */
    public function WithPostParams(array $postParams): IRequest
    {
        return $this->modifyProperty('postParams', $postParams);
    }

    /**
     * Возвращает список загруженных файлов
     * @return array
     */
    public function GetUploadedFiles(): array
    {
        return $this->uploadedFiles;
    }

    /**
     * Возвращает новый объект с изменённым списком загруженных файлов
     * @param array $uploadedFiles
     * @return static
     */
    public function WithUploadedFiles(array $uploadedFiles): IRequest
    {
        return $this->modifyProperty('uploadedFiles', $uploadedFiles);
    }

    /**
     * Возвращает атрибуты запроса
     * @return array
     */
    public function GetAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Возвращает новый объект с изменёнными атрибутами запроса
     * @param array $attributes
     * @return static
     */
    public function WithAttributes(array $attributes): IRequest
    {
        return $this->modifyProperty('attributes', $attributes);
    }

    /**
     * Возвращает объект представляющий uri
     * @return IUri
     */
    public function GetUri(): IUri
    {
        return $this->uri;
    }

    /**
     * Возвращает новый объект с модифицированным uri
     * @param IUri $uri
     * @return static
     */
    public function WithUri(IUri $uri): IRequest
    {
        $new = $this->modifyProperty('uri', $uri);

        $queryStr = $new->uri->GetQuery();

        if($queryStr === ''){
            $new->queryParams = [];
        }else{
            $queryParams = [];
            parse_str($queryStr, $queryParams);
            $new->queryParams = $queryParams;
        }

        return $new;
    }

}