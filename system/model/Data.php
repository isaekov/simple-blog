<?php


namespace System\model;


/**
 * @property mixed title
 */
class Data
{

    private array $properties = [];

    public function __construct(array $data)
    {

    }


    public function __get($name)
    {
        return $this->properties[$name];
    }

    public function __set($name, $value)
    {
        $this->properties[$name] = $value;
    }

}