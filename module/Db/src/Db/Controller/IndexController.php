<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-21 12:06:06
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-21 12:07:13
 */

namespace Db\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $viewModel = new ViewModel();

        return $viewModel;
    }
}
