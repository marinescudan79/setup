<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-27 13:19:33
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-27 14:34:30
 */

namespace Application\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class StorageServicePlugin extends AbstractPlugin
{
    public function __invoke($name)
    {
        return $this->getController()->getServiceLocator()->get('StorageService')->init($name);
    }
}
