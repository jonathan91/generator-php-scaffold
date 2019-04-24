<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\View\Model\JsonModel;

class IndexController extends AppAbstractController
{

    protected function service()
    {
        return $this->getServiceLocator()->get('Application\ApplicationService');
    }

    public function indexAction()
    {
        return new JsonModel();
    }
}
