<?php declare(strict_types=1);
namespace App\Factory;

use Tuupola\Middleware\CorsMiddleware;

class CorsFactory
{

    public function __invoke($container)
    {
        $corsConfig = $container->get('config')['cors'] ?? [];
        return new CorsMiddleware($corsConfig);
    }
}

