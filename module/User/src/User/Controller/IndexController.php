<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-21 01:19:44
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-22 21:54:25
 */

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use User\Form\UserForm;
use User\Form\Filter\UserFilter;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $viewModel = new ViewModel(array());

        return $viewModel;
    }

    public function listAction()
    {
        $users = $this->getService('UserService')->listUsers();
        $viewModel = new ViewModel(array(
            'users' => $users,
        ));
        return $viewModel;
    }

    public function addAction()
    {
        $roles = $this->getService('RoleService')->getRoles();
        // \Zend\Debug\Debug::dump($roles);
        $form = new UserForm;
        $form->get('RoleId')->setValueOptions($roles);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $filter = new UserFilter();
            $filter->getInputFilter()->get('UserName')->getValidatorChain()->attach($this->getServiceLocator()->get('UserExist'));
            $filter->getInputFilter()->get('Email')->getValidatorChain()->attach($this->getServiceLocator()->get('EmailExist'));
            $form->setInputFilter($filter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getService('UserService')->addUser($form->getData());
                $this->flashMessages()->addSuccessMessage('New User Created');
                return new JsonModel(array('reload'));
            } else {
                $this->flashMessages()->setNamespace('error')->addMessage($form->getMessages());
            }
        }

        $viewModel = new ViewModel(array(
            'form' => $form,
        ));

        return $viewModel->setTerminal(true);
    }
}
