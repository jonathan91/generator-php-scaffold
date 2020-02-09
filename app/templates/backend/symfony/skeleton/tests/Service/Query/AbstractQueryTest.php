<?php
namespace App\Tests\Service\Query;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AbstractQueryTest extends KernelTestCase
{
    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;
    
    /**
     *
     * {@inheritdoc}
     * @see \PHPUnit\Framework\TestCase::setUp()
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        
        $this->entityManager = $kernel->getContainer()
        ->get('doctrine')
        ->getManager();
    }
}

