<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-21 02:52:33
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-23 11:43:00
 */

namespace User\Form;

use Application\Utils\Form\FormLayer;

class RoleForm extends FormLayer
{

    public function __construct($name = null)
    {
        parent::__construct('role-add-form');

        $this->add(array(
            'name' => 'RoleName',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control input-sm',
                'required' => 'required',
                'placeholder' => 'Role Name',
            ),
            'options' => array(
                'label' => 'Role Name',
            ),
        ));

        $this->add(array(
            'name'       => 'RoleParentId',
            'type'  => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control input-sm',
            ),
            'options' => array(
                'label' => 'Inherited Role',
                'empty_option' => '- No Inherited Role -',
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
