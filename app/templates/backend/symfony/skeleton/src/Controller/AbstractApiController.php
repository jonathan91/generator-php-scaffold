<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use League\Tactician\CommandBus;
/**
 * 
 * @author basis
 *
 */
abstract class AbstractApiController  extends AbstractController
{
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