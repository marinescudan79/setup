<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-27 11:39:37
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-27 14:34:21
 */

namespace Application\Service\Invokable;

use Zend\Session\Container;
use Zend\Session\SessionManager;
use Application\Utils\Service\AbstractService;

class StorageService extends AbstractService
{
    public function init($name)
    {
        $container = new Container('Acl');
        \Zend\Debug\Debug::dump($container);
    }
}
