<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-21 12:06:06
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-23 02:32:29
 */

namespace Acl\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Acl\Form\ResourceForm;
use Acl\Form\Filter\ResourceFilter;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $viewModel = new ViewModel(array(
        ));

        return $viewModel;
    }
    public function listResourcesAction()
    {
        $resources = $this->getService('ResourceService')->listResources();

        $viewModel = new ViewModel(array(
            'resources' => $resources,
        ));

        return $viewModel;
    }

    public function addResourceAction()
    {
        $form = new ResourceForm();

        $request = $this->getRequest();
        if ($request->isPost()) {

            // \Zend\Debug\Debug::dump($request->getPost());
            $filter = new ResourceFilter();
            $filter->getInputFilter()->get('ResourceEntry')->getValidatorChain()->attach($this->getService('ResourceExist'));
            $form->setInputFilter($filter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getService('ResourceService')->addResource($form->getData());
                $this->flashMessages()->addSuccessMessage('New Resource Created');
                return new JsonModel(array('reload'));
            } else {
                $this->flashMessages()->setNamespace('error')->addMessage($form->getMessages());
            }
        }

        $viewModel = new ViewModel(array(
            'form'      => $form,
        ));

        return $viewModel->setTerminal(true);
    }
}
