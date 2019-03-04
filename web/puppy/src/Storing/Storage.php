<?php
namespace Puppy\Storing;

/**
 * Class Storage
 * @package Puppy\Storing
 */
class Storage implements IStorage{

    private $storage = [];

    /**
     * Storage constructor.
     * @param array $initialStorage
     */
    public function __construct(array $initialStorage = [])
    {
        $this->storage = $initialStorage;
    }

    /**
     * Добавляет в хранилище значение под ключом $name
     * @param string $key
     * @param mixed $value
     */
    public function Set(string $key, $value)
    {
        $this->storage[$key] = $value;
    }

    /**
     * Возвращает объект из хранилища
     * @param string $key
     * @param mixed|null $default
     * @return mixed|$default
     */
    public function Get(string $key, $default = null)
    {
        return (isset($this->storage[$key]) ? $this->storage[$key] : $default);
    }

    /**
     * Проверяет наличие данных под ключом $key в хранилищи
     * @param string $key
     * @return bool
     */
    public function Has(string $key): bool
    {
        return isset($this->storage[$key]);
    }

    /**
     * Удаляет данные из хранилища
     * @param string $key
     */
    public function Delete(string $key)
    {
        unset($this->storage[$key]);
    }

}