<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-21 02:52:33
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-23 01:51:18
 */

namespace Acl\Form;

use Application\Utils\Form\FormLayer;

class ResourceForm extends FormLayer
{

    public function __construct($name = null)
    {
        parent::__construct('resource-add-form');

        $this->add(array(
            'name' => 'ResourceName',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control input-sm',
                'required' => 'required',
                'placeholder' => 'Resource Name',
            ),
            'options' => array(
                'label' => 'Resource Name',
            ),
        ));

        $this->add(array(
            'name' => 'ResourceEntry',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control input-sm',
                'required' => 'required',
                'placeholder' => 'Resource Entry',
            ),
            'options' => array(
                'label' => 'Resource Entry',
            ),
        ));

        $this->add(array(
            'name' => 'SortKey',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control input-sm',
                'placeholder' => 'SortKey',
            ),
            'options' => array(
                'label' => 'SortKey',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'IsExternalUrl',
            'options' => array(
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0',
            ),
            'attributes' => array(
                'class' => "switch",
                'data-on-text'  => "yes",
                'data-off-text' => "no",
                'data-label-text' => "IsExternalUrl",
                'data-size'     => "small",
                'checked'     => false,
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'IsVisibleInMenu',
            'options' => array(
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0',
            ),
            'attributes' => array(
                'class' => "switch",
                'data-on-text'  => "yes",
                'data-off-text' => "no",
                'data-label-text' => "IsVisibleInMenu",
                'data-size'     => "small",
                'checked'     => true,
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
