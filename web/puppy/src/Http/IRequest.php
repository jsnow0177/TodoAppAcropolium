<?php
namespace Puppy\Http;

/**
 * Interface IRequest
 * @package Puppy\Http
 */
interface IRequest extends IMessage{

    /**
     * Возвращает HTTP-метод
     * @return string
     */
    public function GetMethod(): string;

    /**
     * Возвращает новый объект с модифицированным методом
     * @param string $method
     * @return static
     */
    public function WithMethod(string $method): IRequest;

    /**
     * Возвращает массив параметров запроса
     * @return array
     */
    public function GetQueryParams(): array;

    /**
     * Возвращает новый объект с изменёнными параметрами запроса.
     * При модификации параметров запроса изменяется также и объект uri
     * @param array $queryParams
     * @return static
     */
    public function WithQueryParams(array $queryParams): IRequest;

    /**
     * Возвращает массив POST-параметров
     * @return array
     */
    public function GetPostParams(): array;

    /**
     * Возвращает новый объект с изменёнными POST-параметрами
     * @param array $postParams
     * @return static
     */
    public function WithPostParams(array $postParams): IRequest;

    /**
     * Возвращает список загруженных файлов
     * @return array
     */
    public function GetUploadedFiles(): array;

    /**
     * Возвращает новый объект с изменённым списком загруженных файлов
     * @param array $uploadedFiles
     * @return static
     */
    public function WithUploadedFiles(array $uploadedFiles): IRequest;

    /**
     * Возвращает атрибуты запроса
     * @return array
     */
    public function GetAttributes(): array;

    /**
     * Возвращает новый объект с изменёнными атрибутами запроса
     * @param array $attributes
     * @return static
     */
    public function WithAttributes(array $attributes): IRequest;

    /**
     * Возвращает объект представляющий uri
     * @return IUri
     */
    public function GetUri(): IUri;

    /**
     * Возвращает новый объект с модифицированным uri
     * @param IUri $uri
     * @return static
     */
    public function WithUri(IUri $uri): IRequest;

}