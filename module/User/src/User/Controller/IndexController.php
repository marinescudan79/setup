<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-21 01:19:44
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-21 14:21:46
 */

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
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

        $test = $this->getTable('User')->select()->fetchRow();
        $viewModel = new ViewModel(array());
        return $viewModel;
    }

    public function addAction()
    {

        $form = new UserForm;

        $request = $this->getRequest();
        if ($request->isPost()) {
            sleep(1);
            $filter = new UserFilter();
            $form->setInputFilter($filter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->flashMessages()->addSuccessMessage('Your message');
            } else {
                // \Zend\Debug\Debug::dump($this->flashMessages());
                $this->flashMessages()->setNamespace('error')->addMessage($form->getMessages());
            }
        }

        $viewModel = new ViewModel(array(
            'form' => $form,
        ));

        return $viewModel->setTerminal(true);
    }

    private function addUser($post)
    {

            $userName       = $this->lastName.microtime(true);
            $password_clear = rand(0, getrandmax());
            $password       = hash('sha512', $password_clear);

            $contact = $this->getTable('ContactDetail')->select()->where(array('ContactDetailId' => $this->contactDetailId))->fetchRow();
            $address = $this->getTable('Address')->select()->where(array('AddressId' => $contact->AddressId))->fetchRow();
            unset($contact->ContactDetailId, $contact->MailingAddressId, $address->AddressId);
            if ($address) {
                $contact->AddressId = $this->getTable('Address')->insert($address);
            }

            $contactDetailId    = $this->getTable('ContactDetail')->insert($contact);

            $userArray = array(
                'ContactDetailId' => $contactDetailId,
                'UserName'        => $userName,
                'Password'        => $password,
                'Status'          => 'MustSignAgreement',
                'IsFFM'           => 1,
                'IsUserOnly'      => 1,
                'DomainId'        => $this->domainId,
                'RoleId'          => $roleId,
            );
            // create user
            $this->ownerUserId = $this->getTable('User')->insert($userArray);
    }
}
