<?php

namespace Tests\Integration;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use League\Tactician\CommandBus;

abstract class AbstractIntegrationTestCase extends KernelTestCase
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var string
     */
    protected $databaseFile;

    /**
     * @var string
     */
    protected $databaseFileBackup;

    protected function setUp()
    {
        parent::setUp();
        self::bootKernel();
        $this->container = static::$kernel->getContainer();
        $this->em = $this->container->get('doctrine.orm.entity_manager');
        $this->databaseFile = $this->em->getConnection()->getDatabase();
        $this->databaseFileBackup = $this->databaseFile . '.bak';
        $this->createDatabase();
    }

    protected function getService($service)
    {
        return $this->container->get($service);
    }

    /**
     * @return CommandBus
     */
    protected function getServiceBus()
    {
        return $this->container->get('tactician.commandbus');
    }

    protected function createDatabase()
    {
        if (!file_exists($this->databaseFile)) {
            $application = new Application(static::$kernel);
            $application->setAutoExit(false);

            $schemaCreate = [
                'command' => 'doctrine:schema:create',
                '--quiet' => true,
                '--env' => 'test'
            ];

            $fixturesLoad = [
                'command' => 'doctrine:fixtures:load',
                '--quiet' => true,
                '--env' => 'test'
            ];

            $application->run(new ArrayInput($schemaCreate));
            $application->run(new ArrayInput($fixturesLoad));

            $this->backupDatabase();
        }
    }

    protected function backupDatabase()
    {
        copy($this->databaseFile, $this->databaseFileBackup);
    }

    protected function restoreDatabase()
    {
        unlink($this->databaseFile);
        copy($this->databaseFileBackup, $this->databaseFile);
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->em->close();
        $this->em = null;
        $this->restoreDatabase();
    }
}
