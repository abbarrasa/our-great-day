<?php

class Factory
{
    public static function getInstance($entity)
    {
        $classname = "{$entity}Stretagy";

        return new $classname();
    }
}