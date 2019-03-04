<?php
namespace Puppy;

/**
 * Class AbstractModel
 * @package Puppy
 */
class AbstractModel{

    /**
     * @var \PDO
     */
    protected $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

}