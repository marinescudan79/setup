<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-21 14:26:19
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-21 14:38:45
 */

namespace Application\Utils\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class AbstractService implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function getTable($table)
    {
        return $this->getServiceLocator()->get('getTable')->init($table);
    }

    public function getService($service)
    {
        return $this->getServiceLocator()->get($service);
    }
}
