<?php declare(strict_types=1);
namespace App\Factory\<%= _.startCase(className).replace(' ', '') %>;

use Psr\Container\ContainerInterface;
use App\Handler\<%= _.startCase(className).replace(' ', '') %>\<%= attributs.type %>Handler;
use App\Middleware\<%= _.startCase(className).replace(' ', '') %>\<%= attributs.type %>Middleware;

class <%= attributs.type %>MiddlewareFactory
{
    
    public function __invoke(ContainerInterface $container)
    {
        $em = $container->get('doctrine.entity_manager.orm_default');
        $handler = new <%= attributs.type %>Handler($em);
        return new <%= attributs.type %>Middleware($handler);
    }
}