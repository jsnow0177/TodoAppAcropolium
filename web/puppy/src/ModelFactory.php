<?php
namespace Puppy;

/**
 * Class ModelFactory
 * @package Puppy
 */
class ModelFactory{

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * ModelFactory constructor.
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param string $modelClass
     * @return AbstractModel
     * @throws \InvalidArgumentException
     */
    public function Create(string $modelClass): AbstractModel
    {
        if(!class_exists($modelClass))
            throw new \InvalidArgumentException("Invalid model class specified");

        if(is_a($modelClass, AbstractModel::class, true))
            throw new \InvalidArgumentException("Specified model class is not implements " . AbstractModel::class);

        return (new $modelClass($this->pdo));
    }

}