<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-22 01:49:35
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-27 14:29:31
 */
namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use User\Form\LoginForm;
use User\Form\Filter\LoginFilter;
use Zend\Authentication\Adapter\DbTable\CallbackCheck;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;

class AuthController extends AbstractActionController
{

    public function loginAction()
    {
        //if already login, redirect to success page
        if ($this->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('home');
        }

        $form = new LoginForm();
        $this->layout('layout/empty');
        $viewModel = new ViewModel(array(
            'form' => $form,
        ));

        return $viewModel;
    }

    public function authenticateAction()
    {
        $form = new LoginForm();
        $redirect = 'login';

        $request = $this->getRequest();
        if ($request->isPost()) {
            $filter = new LoginFilter();
            $filter->getInputFilter()->get('UserName')->getValidatorChain()->attach($this->getServiceLocator()->get('UserNotExist'));
            $form->setInputFilter($filter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $post = $form->getData();


                $dbAdapter = $this->getServiceLocator()->get('adapter');
                $authAdapter = new AuthAdapter($dbAdapter, 'User', 'UserName', 'Password');

                $this->getAuthService()->setAdapter($authAdapter);
                $this->getAuthService()->setStorage($this->getService('AuthStorage'));

                $this->getAuthService()->getAdapter()->setIdentity($post['UserName'])->setCredential($this->getService('PasswordService')->hash($post['Password']));
                $result = $this->getAuthService()->authenticate();

                if ($result->isValid()) {

                    foreach ($result->getMessages() as $message) {
                        //save message temporary into flashmessenger
                        $this->flashmessenger()->addSuccessMessage($message);
                    }
                    $redirect = 'home';
                    $this->getSessionStorage()->setRememberMe($request->getPost('rememberme'));
                    //set storage again
                    $this->getAuthService()->setStorage($this->getSessionStorage());
                    $identity = $this->getAuthService()->getAdapter()->getResultRowObject(null, array('Password'));
                    $identity->RoleName = $this->getService('RoleService')->getRoleName($identity->RoleId);
                    $userSettings = $this->getService('UserService')->getUserSettings($identity->UserId);
                    if ($userSettings) {
                        foreach ($userSettings as $key => $value) {
                            $identity->$value['SettingName'] = $value['SettingValue'];
                        }
                    }
                    // \Zend\Debug\Debug::dump($identity);
                    $this->getSessionStorage()->write($identity);
                    $this->getAuthService()->setStorage($this->getSessionStorage());
                } else {

                    foreach ($result->getMessages() as $message) {
                        //save message temporary into flashmessenger
                        $this->flashmessenger()->addErrorMessage($message);
                    }
                }

            } else {
                $this->flashMessages()->setNamespace('error')->addMessage($form->getMessages());
            }

        }
        return $this->redirect()->toRoute($redirect);
    }

    public function logoutAction()
    {
        $this->getSessionStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();

        $this->flashmessenger()->addSuccessMessage("You've been logged out");
        return $this->redirect()->toRoute('login');
    }
}
