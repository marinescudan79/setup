<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-10 17:32:49
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-22 17:14:40
 */

namespace User\Service\Validator;

use Zend\Validator\AbstractValidator;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UsernameNotExist extends AbstractValidator implements ServiceLocatorAwareInterface
{

    const ERROR = 'ERROR';

    public $serviceLocator;

    protected $messageTemplates = array(
        self::ERROR => "'%value%' not exist in our system.",
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
            'UserName' => $value,
            new \Zend\Db\Sql\Predicate\Literal("Status <> 'Deleted'"),
        );

        $check = $this->getServiceLocator()->get('getTable')->init('User')->select()->where($where)->fetchRow();

        if (!$check) {
            $this->error(self::ERROR);
            return false;
        }

        return true;
    }
}
