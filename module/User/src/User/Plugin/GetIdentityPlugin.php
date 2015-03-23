<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-22 21:02:32
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-22 21:54:34
 */

namespace User\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class GetIdentityPlugin extends AbstractPlugin
{
    protected $authservice;

    public function __invoke()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getController()->getServiceLocator()->get('AuthService');
        }

        if ($this->authservice->hasIdentity()) {
            return $this->authservice->getIdentity();
        }
        return false;
    }
}
