<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;

abstract class AbstractApiController extends FOSRestController implements ClassResourceInterface
{
	
    public function getServiceBus()
    {
        return $this->get('tactician.commandbus');
    }
}
