<?php
namespace Puppy\Storing;

/**
 * Interface IStorage
 * @package Puppy\Storing
 */
interface IStorage{

    /**
     * Добавляет в хранилище значение под ключом $name
     * @param string $key
     * @param mixed $value
     */
    public function Set(string $key, $value);

    /**
     * Возвращает объект из хранилища
     * @param string $key
     * @param mixed|null $default
     * @return mixed|$default
     */
    public function Get(string $key, $default = null);

    /**
     * Проверяет наличие данных под ключом $key в хранилищи
     * @param string $key
     * @return bool
     */
    public function Has(string $key): bool;

    /**
     * Удаляет данные из хранилища
     * @param string $key
     */
    public function Delete(string $key);

}