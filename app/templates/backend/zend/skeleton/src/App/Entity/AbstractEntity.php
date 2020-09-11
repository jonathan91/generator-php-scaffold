<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @var int
     */
    protected $id;
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

