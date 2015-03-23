<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-21 14:33:46
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-21 14:34:25
 */

namespace Application\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class GetServicePlugin extends AbstractPlugin
{
    public function __invoke($service)
    {
        return $this->getController()->getServiceLocator()->get($service);
    }
}
