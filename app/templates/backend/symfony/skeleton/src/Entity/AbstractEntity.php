<?php
namespace App\Entity;

use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class AbstractEntity
{
    /**
     *
     * @var array
     */
    private $_data;
    
    /**
     *
     * @param array $data
     */
    public function __construct($data = null)
    {
        if(isset($data)){
            $this->setValues($data);
        }
    }
    
    /**
     *
     * @param string $key
     * @param mixed $value
     */
    public function setValue(string $key, $value)
    {
        $this->_data[$key] = $value;
        $method = "set".ucfirst($key);
        if (method_exists($this, $method)) {
            $this->$method($value);
        }
    }
    
    /**
     *
     * @param array $data
     */
    public function setValues($data)
    {
        foreach ($data as $key => $value ) {
            $attributs = array_keys(get_object_vars($this));
            if(in_array($key, $attributs)){
                $this->setValue($key, $value);
            }
        }
    }
    
    /**
     *
     * @param string $key
     * @return mixed
     */
    public function getValue(string $key)
    {
        return $this->_data[$key];
    }
    
    /**
     * 
     * @return string
     */
    public function toJson()
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        return $serializer->serialize($this, 'json');
    }
    /**
     * 
     * @return array
     */
    public function toArray() {
        return get_object_vars($this);
    }
}

