<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
// use SensioLabs\Security\SecurityChecker;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        // $checker = new SecurityChecker();
        // $alerts = $checker->check(getcwd().'/composer.lock');
        // if (!empty($alerts)) {
        //     \Zend\Debug\Debug::dump($alerts);
        // }
        // $test = $this->getStorage('Acl');
        // \Zend\Debug\Debug::dump($test);
        return new ViewModel();
    }
}
