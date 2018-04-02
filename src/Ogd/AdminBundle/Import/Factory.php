<?php

class Factory
{
    public static function getInstance($className)
    {
        $name = $this->classToTableName($className) . 'Strategy';

        return (new \ReflectionClass($name))->newInstance();
    }
    
    protected function classToTableName($className)
    {
        if (strpos($className, '\\') !== false) {
            return substr($className, strrpos($className, '\\') + 1);
        }

        return $className;
    }
}
