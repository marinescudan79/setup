<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-23 09:41:34
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-27 10:27:22
 */

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use User\Form\RoleForm;
use User\Form\Filter\RoleFilter;

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
        $roles = $this->getService('RoleService')->getInheritedRoles();
        $viewModel = new ViewModel(array(
            'roles' => $roles,
        ));

        return $viewModel;
    }

    public function manageRoleAction()
    {
        $id = $this->params()->fromRoute('id', false);

        if (!$id) {
            throw new Exception("Error Processing Request", 1);
        }

        $list = $this->getService('ResourceService')->listModuleResources();

        $roleResourceList = $this->getService('ResourceService')->listRoleResources($id);

        $diff = $this->getService('ResourceService')->rolePrivilegesDiff($list, $roleResourceList);

        $request = $this->getRequest();

        if ($request->isPost()) {

            // $roleResource = array();
            $roleResourcePrivilege = array();
            $mvcRequest     = new \Zend\Http\Request();
            $mvcRouter      = $this->getServiceLocator()->get('router');

            $postResources = $request->getPost()->resources;

            if (!empty($postResources)) {

                foreach ($postResources as $resource) {
                    $mvcRequest->setUri($resource);
                    $match = $mvcRouter->match($mvcRequest);

                    $parts = explode('\\', $match->getParams()['__NAMESPACE__']);
                    $module = $parts[0];

                    $roleResource = $match->getParams()['__NAMESPACE__'].'\\'.ucfirst($match->getParams()['controller']);
                    $roleResourcePrivilege[$module][$roleResource][] = $resource;
                }
            }

            $this->getService('ResourceService')->saveRoleResources($id, $roleResourcePrivilege);

            return $this->redirect()->toRoute('user/default', array('controller' => 'role', 'action' => 'manage-role', 'id' => $id));
        }

        $viewModel = new ViewModel(array(
            'list' => $diff,
            'roleResourceList' => $roleResourceList,
        ));

        return $viewModel;
    }

    public function addRoleAction()
    {
        $form = new RoleForm();
        $roles = $this->getService('RoleService')->getInheritedRoles();

        $form->get('RoleParentId')->setValueOptions($roles);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $filter = new RoleFilter();
            $filter->getInputFilter()->get('RoleName')->getValidatorChain()->attach($this->getServiceLocator()->get('RoleExist'));
            $form->setInputFilter($filter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getService('RoleService')->addRole($form->getData());
                $this->flashMessages()->addSuccessMessage('New Role Created');
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
