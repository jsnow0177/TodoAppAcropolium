<?php
namespace Puppy\Storing;

/**
 * Class Config
 * @package Puppy\Storing
 */
class Config extends Storage{

    /**
     * Загружает конфигурацию из PHP-файла
     * @param string $filePath
     * @return Config
     */
    public static function FromPhp(string $filePath): Config
    {
        if(!file_exists($filePath))
            throw new PhpConfigFileNotFoundException("Config file {$filePath} not found");

        $array = @include($filePath);

        return new static($array);
    }

    /**
     * Config constructor.
     * @param array $initialConfig
     */
    public function __construct(array $initialConfig = [])
    {
        parent::__construct($initialConfig);
    }

    /**
     * @param array $config
     */
    public function UpdateConfig(array $config)
    {
        foreach($config as $k => $v){
            $this->Set($k, $v);
        }
    }

}