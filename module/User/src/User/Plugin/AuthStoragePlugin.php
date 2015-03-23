<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-22 21:02:32
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-22 21:11:06
 */

namespace User\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class AuthStoragePlugin extends AbstractPlugin
{
    protected $storage;

    public function __invoke()
    {
        if (! $this->storage) {
            $this->storage = $this->getController()->getServiceLocator()->get('AuthStorage');
        }

        return $this->storage;
    }
}
