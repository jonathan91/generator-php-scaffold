<?php
namespace <%= packageName %>\Controller;

use <%= packageName %>\Entity\<%= className %>;
use Zend\View\Model\JsonModel;
use Application\Controller\AppAbstractController;
use Application\Http\AppHttpResponse;

class <%= className %>Controller extends AppAbstractController
{

    public function create($request)
    {
		$entity = new <%= className %>($request);
    	$entity = $this->getServiceManager()->add($entity);
    	$response = new AppHttpResponse($entity);
    	return new JsonModel($response->getData());
    }

    public function update($id, $request)
    {
			$entity = new <%= className %>($request);
    	$entity->setId($id);
    	$entity = $this->getServiceManager()->edit($entity);
    	$response = new AppHttpResponse($entity);
    	return new JsonModel($response->getData());
    }

}
