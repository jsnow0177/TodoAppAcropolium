<?php
namespace Puppy\DI;

/**
 * Interface IContainer
 * @package Puppy\DI
 */
interface IContainer{

    /**
     * Добавляет в контейнер определение объекта.
     * Если в контейнере уже есть определение объекта и флаг $override не установлен, будет выброшено исключение.
     *
     * @param string $id
     * @param callable|\Closure|object|string $definition Функция, объект или имя класса
     * @param array $args Аргументы передаваемые в функции или в конструктор объекта
     * @param bool $override Флаг указывает на то, необходимо ли перезаписывать определение в контейнере, если оно уже там есть
     * @throws \InvalidArgumentException
     * @throws OverrideExistentDefinitionException
     */
    public function Set(string $id, $definition, array $args = [], bool $override = false);

    /**
     * Возвращает объект из контейнера.
     * @param string $id
     * @param bool $shared
     * @return object
     * @throws NotFoundException
     * @throws FreshInstanceException
     */
    public function Get(string $id, bool $shared = false): object;

    /**
     * Проверяет наличие объекта в контейнере
     * @param string $id
     * @return bool
     */
    public function Has(string $id): bool;

}