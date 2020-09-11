<?php
namespace App\Service\Handler;

use App\Entity\AbstractEntity;
use App\Service\Command\AbstractCommand;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    /**
     *
     * @param AbstractEntity $entity
     * @param AbstractCommand $command
     * @return AbstractEntity|ConstraintViolationListInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function post(AbstractEntity $entity, AbstractCommand $command)
    {
        $error = $this->validator->validate($command);
        if($error->count() == 0) {
            $entity->setValues($command->toArray());
            $this->em->persist($entity);
            $this->em->flush();
            return $entity;
        }
        return $error;
    }

    /**
     *
     * @param AbstractEntity $entityInput
     * @param AbstractCommand $command
     * @return object|ConstraintViolationListInterface|null
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function put(AbstractEntity $entityInput, AbstractCommand $command)
    {
        $entity = $this->em->getRepository(get_class($entityInput))->find($command->id);
        if(empty($entity)){
            throw new NotFoundHttpException("The record with ID: {$command->id} can't identified.");
        }
        $error = $this->validator->validate($command);
        if($error->count() == 0) {
            $entity->setValues($command->toArray());
            $this->em->persist($entity);
            $this->em->flush();
            return $entity;
        }
        return $error;
    }

    /**
     *
     * @param AbstractEntity $entityInput
     * @param AbstractCommand $command
     * @return array|ConstraintViolationListInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(AbstractEntity $entityInput, AbstractCommand $command)
    {
        $error = $this->validator->validate($command);
        if ($error->count() == 0) {
            $entity = $this->em->getRepository(get_class($entityInput))->find($command->id);
            if (empty($entity)) {
                throw new NotFoundHttpException("The record with ID: {$command->id} can't identified.");
            }
            $this->em->remove($entity);
            $this->em->flush();
            return [
                "The record with ID: {$command->id} was deleted."
            ];
        }
        return $error;
    }

    /**
     *
     * @param AbstractEntity $entityInput
     * @param AbstractCommand $command
     * @return object|ConstraintViolationListInterface|NULL
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function patch(AbstractEntity $entityInput, AbstractCommand $command)
    {
        return $this->put($entityInput, $command);
    }
}
