<?php

namespace AppBundle\Http;

class ResponseBag
{
    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var bool
     */
    protected $success;

    /**
     * @var string
     */
    protected $message;

    public function __construct($data = null, $success = true, $message = null)
    {
        $this->data = $data;
        $this->success = $success;
        $this->message = $message;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getSuccess()
    {
        return $this->success;
    }

    public function setMessage(string $message)
    {
        $this->message = $message;
        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setData(Mixed $data)
    {
        $this->data = $data;
        return $this;
    }

    public function setSuccess(bool $success)
    {
        $this->success = $success;
        return $this;
    }
}
