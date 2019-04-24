<?php

namespace AppBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
class AppBundle extends Bundle
{

	public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }
}
