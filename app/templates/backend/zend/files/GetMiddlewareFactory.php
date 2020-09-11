<?php declare(strict_types=1);
namespace App\Factory\<%=_.startCase(className).replace(' ', '')%>;

use Psr\Container\ContainerInterface;
use App\Query\<%= _.startCase(className).replace(' ', '') %>Query;
use App\Middleware\<%= _.startCase(className).replace(' ', '') %>\GetMiddleware;

class GetMiddlewareFactory
{
    
    public function __invoke(ContainerInterface $container)
    {
        $em = $container->get('doctrine.entity_manager.orm_default');
        $query = new <%= _.startCase(className).replace(' ', '') %>Query($em);
        return new GetMiddleware(null, $query);
    }
}