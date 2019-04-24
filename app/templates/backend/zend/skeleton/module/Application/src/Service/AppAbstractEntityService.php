<?php
namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceManager;
use Zend\Form\Annotation\AnnotationBuilder;
use Application\Exceptions\ValidationException;

abstract class AppAbstractEntityService
{
    /**
     * 
     * @var EntityManager
     */
    protected $ormEntityManager;
   
    public function __construct(EntityManager $ormEntityManager)
    {
        $this->ormEntityManager = $ormEntityManager;
    }
    
    public function getOrmEntityManager()
    {
        return $this->ormEntityManager;
    }
    
    public function add($entity)
    {
        try {
            $this->validation($entity);
            $this->getOrmEntityManager()->persist($entity);
            $this->getOrmEntityManager()->flush();
        } catch (\Exception $exceptionError) {
            throw new \Exception($exceptionError->getMessage(), $exceptionError->getCode());
        }
        return $entity;
    }
    
    public function edit($entity)
    {
        try {
            $this->validation($entity);
            $this->getOrmEntityManager()->merge($entity);
            $this->getOrmEntityManager()->flush();
        } catch (\Exception $exceptionError) {
            throw new \Exception($exceptionError->getMessage(), $exceptionError->getCode());
        }
        return $entity;
    }
    
    public function delete($id)
    {
        try {
            $entity = $this->find($id);
            $this->getOrmEntityManager()->remove($entity);
            $this->getOrmEntityManager()->flush();
        } catch (\Exception $exceptionError) {
            throw new \Exception($exceptionError->getMessage(), $exceptionError->getCode());
        }
    }

    public function validation($entity)
    {
    	$annotation = new AnnotationBuilder();
    	$entityForm = $annotation->createForm($entity);
    	$entityForm->bind($entity);
    	$entityForm->setData($entity->toArray());
    	if (!$entityForm->isValid()){
    		throw new ValidationException($entityForm->getMessages());
    	}
    }
}
