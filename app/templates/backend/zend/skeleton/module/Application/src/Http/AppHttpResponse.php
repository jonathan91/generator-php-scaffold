<?php
namespace Application\Http;

final class AppHttpResponse
{
	/**
	 * 
	 * @var mixed
	 */
	public $data;
	/**
	 * 
	 * @var boolean
	 */
	public $success;
	/**
	 * 
	 * @var string
	 */
	public $message;
	
	/**
	 * @return mixed
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @return boolean
	 */
	public function isSuccess()
	{
		return $this->success;
	}

	/**
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * @param mixed $data
	 */
	public function setData($data)
	{
		if(is_array($data)){
			$this->data['data'] = $this->asArray($data);
		}else{
			$this->data['data'] = $data->toArray();
		}
	}

	/**
	 * @param boolean $success
	 */
	public function setSuccess($success)
	{
		$this->success = $success;
	}

	/**
	 * @param string $message
	 */
	public function setMessage($message)
	{
		$this->message = $message;
	}

	public function __construct($data = null, $success = true, $message = null)
	{
		$this->setData($data);
		$this->setMessage($message);
		$this->setSuccess($success);
	}
	
	private function asArray($data)
	{
		foreach ($data as $value){
			if(is_array($value)){
				$this->asArray($value);
			}else{
				$result[] = $value->toArray();
			}
		}
		return $result;
	}
}
