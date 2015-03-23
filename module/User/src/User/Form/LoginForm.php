<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-22 16:53:33
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-22 20:52:26
 */

namespace User\Form;

use Application\Utils\Form\FormLayer;

class LoginForm extends FormLayer
{

    public function __construct($name = null)
    {
        parent::__construct('login-form');
        $this->setAttributes(array('method' => 'post', 'action' => '/authenticate', 'class' => "form-signin"));

        $this->add(array(
            'name' => 'UserName',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'placeholder' => 'Username',
            ),
            'options' => array(
                'label' => 'Username',
            ),
        ));

        $this->add(array(
            'name' => 'Password',
            'attributes' => array(
                'type'  => 'password',
                'class' => 'form-control',
                'required' => 'required',
                'placeholder' => 'Password',
            ),
            'options' => array(
                'label' => 'Password',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'rememberme',
            'options' => array(
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0'
            )
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
