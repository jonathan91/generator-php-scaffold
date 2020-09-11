<?php
namespace App\Command;

abstract class AbstractCommand
{
    /**
     *
     * @var array
     */
    private $_data;
    
    public abstract function validate();
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
        $this->$key = $value;
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
     * @return array
     */
    public function getValues()
    {
        return $this->_data;
    }
    
    /**
     *
     * @return mixed
     */
    public function toJson()
    {
        $data = get_object_vars($this);
        unset($data['_data']);
        return json_decode(json_encode($data));
    }
    /**
     *
     * @return array
     */
    public function toArray() {
        $data = get_object_vars($this);
        unset($data['_data']);
        return $data;
    }
}

