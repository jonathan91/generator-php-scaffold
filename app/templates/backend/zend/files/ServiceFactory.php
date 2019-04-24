<?php
namespace <%= packageName %>\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use <%= packageName %>\Service\<%= className %>Service;

class <%= className %>ServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        
        return new <%= className %>Service($entityManager);
    }
}
