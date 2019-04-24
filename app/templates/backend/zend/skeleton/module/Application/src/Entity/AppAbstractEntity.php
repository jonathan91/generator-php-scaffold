<?php
namespace Application\Entity;

use Zend\Di\Exception\ClassNotFoundException;

class AppAbstractEntity
{
    
    public function __get($property)
    {
        return $this->$property;
    }

    public function __set($property, $value)
    {
        $this->$property = $value;
    }
    
    public function setValues($data)
    {
        foreach ($data as $key => $value) {
            $method = "set".str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
            if(method_exists($this, $method)){
                $this->$method(isset($data[$key]) ? $value : null);
            }
        }
    }
    
    public function toArray()
    {
        $properties = [];
        foreach (get_object_vars($this) as $property=>$value) {
            $properties[$property] = $value;
        }
        return $properties;
    }
}
