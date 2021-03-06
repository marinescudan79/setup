<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-22 21:02:32
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-22 21:54:44
 */

namespace User\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class AuthServicePlugin extends AbstractPlugin
{
    protected $authservice;

    public function __invoke()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getController()->getServiceLocator()->get('AuthService');
        }

        return $this->authservice;
    }
}
