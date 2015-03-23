<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-23 09:41:34
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-23 09:57:25
 */

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use User\Form\RoleForm;
// use User\Form\Filter\UserFilter;

class RoleController extends AbstractActionController
{
    public function indexAction()
    {
        $viewModel = new ViewModel(array(

        ));

        return $viewModel;
    }

    public function listRolesAction()
    {
        $viewModel = new ViewModel(array(

        ));

        return $viewModel;
    }

    public function addRoleAction()
    {
        $form = new RoleForm();

        $viewModel = new ViewModel(array(
            'form' => $form,
        ));

        return $viewModel->setTerminal(true);
    }
}
