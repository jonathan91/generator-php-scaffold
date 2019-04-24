<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;

class Module
{
    const VERSION = '3.0.3-dev';

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'onDispatchError'], 0);
        $eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, [$this, 'onRenderError'], 0);
    }
    public function onDispatchError($e)
    {
    	return $this->getJsonModelError($e);
    }
    
    public function onRenderError($e)
    {
    	return $this->getJsonModelError($e);
    }
    
    public function getJsonModelError($e)
    {
    	$error = $e->getError();
    	if (!$error) {
    		return;
    	}
    	
    	$response = $e->getResponse();
    	$exception = $e->getParam('exception');
    	$exceptionJson = array();
    	if ($exception) {
    		$exceptionJson = array(
    			'class' => get_class($exception),
    			'file' => $exception->getFile(),
    			'line' => $exception->getLine(),
    			'message' => $exception->getMessage(),
    			'stacktrace' => $exception->getTraceAsString()
    		);
    	}
    	
    	$errorJson = [
    		'message'   => 'An error occurred during execution; please try again later.',
    		'error'     => $error,
    		'exception' => $exceptionJson,
    	];
    	if ($error == 'error-router-no-match') {
    		$errorJson['message'] = 'Resource not found.';
    	}
    	
    	$model = new JsonModel(['errors' => [$errorJson]]);
    	
    	$e->setResult($model);
    	
    	return $model;
    }

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * @return mixed
     */
    public function getServiceConfig()
    {
        return include __DIR__ . '/../config/service.config.php';
    }
}
