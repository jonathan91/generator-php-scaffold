<?php
namespace Application\Exceptions;

class AppValidationException extends \Exception
{
	public function __construct($message = [])
	{
		$this->extractMessage($message);
	}
	
	private function extractMessage($messages)
	{
		foreach ($messages as $filed=>$rules){
			foreach ($rules as $message){
				$this->message .= ucfirst(strtolower($filed.' '.$message));
			}
		}
	}
}
