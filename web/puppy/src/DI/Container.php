<?php
namespace Puppy\DI;

/**
 * Class Container
 * @package Puppy\DI
 */
class Container implements IContainer{

    /**
     * @var array[]
     */
    private $definitions = [];

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
    public function Set(string $id, $definition, array $args = [], bool $override = false)
    {
        if(isset($this->definitions[$id]) && !$override)
            throw new OverrideExistentDefinitionException("Definition {$id} already exists in container");

        if(!is_callable($definition) && !is_object($definition) && (!is_string($definition) || !class_exists($definition)))
            throw new \InvalidArgumentException("Invalid definition");

        $this->definitions[$id] = [
            'callable' => is_callable($definition) ? $definition : null,
            'instance' => (is_object($definition) && !($definition instanceof \Closure)) ? $definition : null,
            'className' => is_string($definition) ? $definition : '',
            'onlyInstance' => (is_object($definition) && !($definition instanceof \Closure)) ? true : false,
            'args' => $args
        ];
    }

    /**
     * Возвращает объект из контейнера.
     * @param string $id
     * @param bool $shared
     * @return object
     * @throws NotFoundException
     * @throws FreshInstanceException
     */
    public function Get(string $id, bool $shared = true): object
    {
        if(!isset($this->definitions[$id]))
            throw new NotFoundException("Definition {$id} not found in container");

        if($this->definitions[$id]['onlyInstance']){
            if(!$shared)
                throw new FreshInstanceException("Can't create fresh instance for {$id}");

            return $this->definitions[$id]['instance'];
        }

        if($shared && !is_null($this->definitions[$id]['instance']))
            return $this->definitions[$id]['instance'];

        if($shared){
            $this->definitions[$id]['instance'] = $this->makeInstance($id);
            return $this->definitions[$id]['instance'];
        }

        return $this->makeInstance($id);
    }

    /**
     * Проверяет наличие объекта в контейнере
     * @param string $id
     * @return bool
     */
    public function Has(string $id): bool
    {
        return isset($this->definitions[$id]);
    }

    /**
     * @param string $id
     * @return object
     */
    private function makeInstance(string $id): object
    {
        ['callable' => $callable, 'className' => $className, 'args' => $args] = $this->definitions[$id];

        if(!is_null($callable)){
            array_unshift($args, $this);
            return call_user_func_array($callable, $args);
        }

        return new $className(...$args);
    }

}