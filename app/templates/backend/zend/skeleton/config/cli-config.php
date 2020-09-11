<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require __DIR__ .'/../vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'container.php';

$em = $container->get('doctrine.entity_manager.orm_default');

return ConsoleRunner::createHelperSet($em);