<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-29 18:51:51
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-31 14:55:05
 */

namespace Navigation\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {

        // $navigation = $this->getService('NavigationService')->getNavigationItems('MenuNavigation');

        // \Zend\Debug\Debug::dump($navigation);
        $viewModel = new ViewModel(array());

        return $viewModel;
    }
}
