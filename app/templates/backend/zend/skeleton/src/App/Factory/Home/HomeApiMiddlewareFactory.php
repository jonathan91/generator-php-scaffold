<?php
namespace App\Factory\Product;

use Psr\Container\ContainerInterface;
use App\Middleware\Home\HomeMiddleware;

class HomeApiMiddlewareFactory
{

    public function __invoke(ContainerInterface $container)
    {
        return new HomeMiddleware();
    }
}

