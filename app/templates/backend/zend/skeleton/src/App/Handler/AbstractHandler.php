<?php
namespace App\Handler;

use App\Command\AbstractCommand;
use App\Entity\AbstractEntity;
use Doctrine\ORM\EntityManager;
use Laminas\Http\Response;
use Zend\Diactoros\Response\JsonResponse;

abstract class AbstractHandler
{
    
    /**
     * 
     * @var EntityManager
     */
    protected $em;
    
    /**
     * 
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    /**
     * 
     * @param AbstractEntity $entity
     * @param AbstractCommand $command
     */
    protected abstract function process(AbstractEntity $entity, AbstractCommand $command);
    
    /**
     * 
     * @param string $msg
     * @return mixed
     */
    protected function message($msg)
    {
        $msg = [
            'detail' => $msg
        ];
        return json_decode(json_encode($msg));
    }
    /**
     * 
     * @param AbstractEntity $entity
     * @param AbstractCommand $command
     * @return JsonResponse
     */
    protected function post(AbstractEntity $entity, AbstractCommand $command): JsonResponse
    {
        try {
            $isValid = $command->validate();
            if($isValid) {
                $entity->setValues($command->toArray());
                $this->em->persist($entity);
                $this->em->flush();
                return new JsonResponse($entity->toJson(), Response::STATUS_CODE_200);
            }
        }
        catch (\Exception $error) {
            return new JsonResponse($this->message($error->getMessage()), Response::STATUS_CODE_400);
        }
    }
    
    /**
     * 
     * @param AbstractEntity $entity
     * @param AbstractCommand $command
     * @return \Zend\Diactoros\Response\JsonResponse
     */
    protected function put(AbstractEntity $entity, AbstractCommand $command)
    {
        try {
            $entity = $this->em->getRepository(get_class($entity))->find($command->id);
            if(empty($entity)){
                return new JsonResponse($this->message("The record with ID: {$command->id} can't identified."), Response::STATUS_CODE_400);
            }
            $isValid = $command->validate();
            if($isValid) {
                $entity->setValues($command->toArray());
                $this->em->persist($entity);
                $this->em->flush();
                return new JsonResponse($entity->toJson(), Response::STATUS_CODE_200);
            }
        } catch(\Exception $error) {
            return new JsonResponse($this->message($error->getMessage()), Response::STATUS_CODE_400);
        }
    }
    
    /**
     * 
     * @param AbstractEntity $entity
     * @param AbstractCommand $command
     * @return \Zend\Diactoros\Response\JsonResponse|string
     */
    protected function delete(AbstractEntity $entity, AbstractCommand $command)
    {
        try {
            $isValid = $command->validate();
            if ($isValid) {
                $entity = $this->em->getRepository(get_class($entity))->find($command->id);
                if (empty($entity)) {
                    return new JsonResponse($this->message("The record with ID: {$command->id} can't identified."), Response::STATUS_CODE_400);
                }
                $this->em->remove($entity);
                $this->em->flush();
                return new JsonResponse("The record with ID: {$command->id} was deleted.", Response::STATUS_CODE_200);
            }
        } catch (\Exception $error) {
            return new JsonResponse($this->message($error->getMessage()), Response::STATUS_CODE_400);
        }
    }
    
    /**
     * 
     * @param AbstractEntity $entityInput
     * @param AbstractCommand $command
     * @return \Zend\Diactoros\Response\JsonResponse
     */
    protected function patch(AbstractEntity $entityInput, AbstractCommand $command)
    {
        return $this->put($entityInput, $command);
    }
}

