<?php
namespace App\Factory\Home;

use Psr\Container\ContainerInterface;
use App\Middleware\Home\HomeMiddleware;

class HomeMiddlewareFactory
{
    
    public function __invoke(ContainerInterface $container)
    {
        return new HomeMiddleware();
    }
}

