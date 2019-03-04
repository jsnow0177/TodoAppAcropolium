<?php
namespace Puppy\ObjectValue;

/**
 * Trait TObjectValue
 * @package Puppy
 */
trait TObjectValue{

    /**
     * @param string $propertyName
     * @param $propertyValue
     * @return static
     * @throws ModifyNotExistentPropertyException
     */
    protected function modifyProperty(string $propertyName, $propertyValue)
    {
        if(!property_exists($this, $propertyName))
            throw new ModifyNotExistentPropertyException("Can't modify property {$propertyName}."); //@codeCoverageIgnore

        $new = clone $this;
        $new->{$propertyName} = $propertyValue;

        return $new;
    }

}