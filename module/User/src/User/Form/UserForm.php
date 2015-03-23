<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-21 02:52:33
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-21 23:27:53
 */

namespace User\Form;

use Application\Utils\Form\FormLayer;

class UserForm extends FormLayer
{

    public function __construct($name = null)
    {
        parent::__construct('user-add-form');

        $this->add(array(
            'name' => 'FirstName',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control input-sm',
                'required' => 'required',
                'placeholder' => 'First Name',
            ),
            'options' => array(
                'label' => 'First Name',
            ),
        ));

        $this->add(array(
            'name' => 'LastName',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control input-sm',
                'required' => 'required',
                'placeholder' => 'Last Name',
            ),
            'options' => array(
                'label' => 'Last Name',
            ),
        ));

        $this->add(array(
            'name' => 'MiddleName',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control input-sm',
                'placeholder' => 'Middle',
            ),
            'options' => array(
                'label' => 'Middle Name',
            ),
        ));

        $this->add(array(
            'name' => 'UserName',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control input-sm',
                'placeholder' => 'Username',
            ),
            'options' => array(
                'label' => 'Username',
            ),
        ));


        $this->add(array(
            'name' => 'Email',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control input-sm',
                'required' => 'required',
                'placeholder' => 'Email Address',
            ),
            'options' => array(
                'label' => 'Email Address',
            ),
        ));

        $this->add(array(
            'name' => 'Password',
            'attributes' => array(
                'type'  => 'password',
                'class' => 'form-control input-sm',
                'required' => 'required',
                'placeholder' => 'Password',
            ),
            'options' => array(
                'label' => 'Password',
            ),
        ));

        $this->add(array(
            'name'       => 'PasswordVerify',
            'attributes' => array(
                'type'  => 'password',
                'class' => 'form-control input-sm',
                'required' => 'required',
                'placeholder' => 'Confirm Password',
            ),
            'options' => array(
                'label' => 'Confirm Password',
            ),
        ));

        $this->add(array(
            'name'       => 'RoleId',
            'type'  => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control input-sm',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'User Role',
                'empty_option' => '- Select Role -',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 600
                )
            )
        ));

    }
}
