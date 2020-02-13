<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use League\Tactician\CommandBus;

abstract class AbstractApiController  extends AbstractController
{
    const SUCCESS = 200;

    const REDIRECT = 301;

    const REDIRECT_SAME = 302;

    const UNAUTHORIZED = 401;

    const FORBIDDEN = 403;

    const NOT_FOUND = 404;

    const INTERNAL_SERVER_ERROR = 500;

    const SERVICE_UNAVAILABLE = 503;

    const BAD_REQUEST = 400;
    /**
     * 
     * @var CommandBus
     */
    protected $command;
    /**
     * 
     * @param CommandBus $command
     */
    public function __construct(CommandBus $command)
    {
        //add validação de jwt
        $this->command = $command;
    }
   
    /**
     * 
     * @return mixed
     */
    public function getServiceBus()
    {
        return $this->command;
    }
    abstract public function post(Request $request);
    
    abstract public function put($id, Request $request);
    
    abstract public function delete($id, Request $request);
    
}