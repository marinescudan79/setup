<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-21 02:52:33
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-21 14:13:43
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
            // 'validators' => array(
            //     array(
            //         'name'    => 'Identical',
            //         'options' => array(
            //             'token' => 'Password',
            //         ),
            //     ),
            // ),
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
