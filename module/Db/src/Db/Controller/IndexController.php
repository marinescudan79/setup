<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-21 12:06:06
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-28 01:27:00
 */

namespace Db\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {

        $controllers = array_keys($this->getService('config')['controllers']['invokables']);

        foreach ($controllers as $controler) {
            $check = $this->getTable('Resource')->select()->where(array('ResourceEntry' => $controler, 'Status' => 'Active'))->fetchRow();
            if (!$check) {
                $this->getTable('Resource')->insert(array('ResourceName' => $controler, 'ResourceEntry' => $controler));
            }
        }

        \Zend\Debug\Debug::dump($controllers);
        $viewModel = new ViewModel();

        return $viewModel;
    }
}
