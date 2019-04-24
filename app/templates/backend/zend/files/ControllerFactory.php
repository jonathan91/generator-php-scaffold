<?php
namespace <%= packageName %>\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use <%= packageName %>\Service\<%= className %>Service;
use <%= packageName %>\Controller\<%= className %>Controller;

class <%= className %>ControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $serviceManager = $container->get(<%= className %>Service::class);
        return new <%= className %>Controller($entityManager, $serviceManager);
    }
}
