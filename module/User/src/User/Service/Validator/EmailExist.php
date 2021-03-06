<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-10 17:32:49
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-21 20:22:59
 */

namespace User\Service\Validator;

use Zend\Validator\AbstractValidator;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EmailExist extends AbstractValidator implements ServiceLocatorAwareInterface
{

    const ERROR = 'ERROR';

    public $serviceLocator;

    protected $messageTemplates = array(
        self::ERROR => "'%value%' already exist in our system.",
    );

    public function setServiceLocator(ServiceLocatorInterface $sl)
    {
        $this->serviceLocator = $sl;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function isValid($value)
    {
        $this->setValue($value);

        if (empty($this->serviceLocator)) {
            return true;
        }

        $where = array(
            'Email' => $value,
        );

        $check = $this->getServiceLocator()->get('getTable')->init('ContactDetail')->select()->where($where)->fetchRow();

        if ($check) {
            $this->error(self::ERROR);
            return false;
        }

        return true;
    }
}
