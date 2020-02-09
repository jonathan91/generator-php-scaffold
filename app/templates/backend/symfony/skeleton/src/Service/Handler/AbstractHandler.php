<?php
namespace App\Service\Handler;

use Doctrine\ORM\EntityManager;
use App\Service\Command\AbstractCommand;
use Symfony\Component\Validator\Validator\ValidatorInterface;
/**
 * 
 * @author basis
 *
 */
abstract class AbstractHandler
{

    /**
     * 
     * @var EntityManager
     */
    protected $em;

    /**
     *
     * @var ValidatorInterface
     */
    protected $validator;
    /**
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->validator = $validator;
    }
    
    abstract public function handle(AbstractCommand $command);
}
