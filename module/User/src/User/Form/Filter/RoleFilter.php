<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-21 03:10:57
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-23 11:50:28
 */

namespace User\Form\Filter;

 use Zend\InputFilter\InputFilter;
 use Zend\InputFilter\InputFilterAwareInterface;
 use Zend\InputFilter\InputFilterInterface;

class RoleFilter implements InputFilterAwareInterface
{
     protected $inputFilter;

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
             $inputFilter = new InputFilter();

             $inputFilter->add(array(
                 'name'     => 'RoleName',
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                 ),
                 'validators' => array(
                     array(
                         'name'    => 'StringLength',
                         'options' => array(
                             'encoding' => 'UTF-8',
                             'min'      => 1,
                             'max'      => 100,
                         ),
                     ),
                 ),
             ));

             $inputFilter->add(array(
                 'name'     => 'RoleParentId',
                 'required' => false,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                 ),
                 'validators' => array(
                     array(
                         'name'    => 'Int',
                         'options' => array(
                             'encoding' => 'UTF-8',
                             'min'      => 1,
                             'max'      => 100,
                         ),
                     ),
                 ),
             ));

             $this->inputFilter = $inputFilter;
        }

         return $this->inputFilter;
    }
}
