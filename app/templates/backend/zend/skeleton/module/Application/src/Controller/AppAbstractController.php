<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Application\Http\AppHttpResponse;
use Zend\View\Model\JsonModel;

abstract class AppAbstractController extends AbstractRestfulController
{
	private $entityManager;
	private $serviceManager;

	public function __construct($entityManager, $serviceManager)
	{
		$this->entityManager = $entityManager;
		$this->serviceManager = $serviceManager;
	}

	public function getServiceManager()
	{
		return $this->serviceManager;
	}

	public function getEntityManager()
	{
		return $this->entityManager;
	}

	public function getList()
	{
	    $entities = $this->getServiceManager()->search();
	    $response = new AppHttpResponse($entities);
	    return new JsonModel($response->getData());
	}
	
	public function get($id)
	{
	    $entity = $this->getServiceManager()->find($id);
	    $response = new AppHttpResponse($entity);
	    return new JsonModel($response->getData());
	}
	
	public function delete($id)
	{
	    $this->getServiceManager()->delete($id);
	    return new JsonModel(['data' => "Record with id {$id} was deleted"]);
	}
}

