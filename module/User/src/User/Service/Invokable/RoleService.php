<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-21 21:24:11
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-23 10:14:08
 */

namespace User\Service\Invokable;

use Application\Utils\Service\AbstractService;

class RoleService extends AbstractService
{
    public function getRoles($form = false, $showDeleted = false)
    {
        $where = array(
            new \Zend\Db\Sql\Predicate\Expression("Role.Status <> 'Deleted'")
        );

        $roles = $this->getTable('Role')->fetchKeyValue(array('RoleId', 'RoleName'));

        return $roles;
    }

    public function getInheritedRoles($showDeleted = false)
    {

        $where = array(
            new \Zend\Db\Sql\Predicate\Expression("Role.Status <> 'Deleted'")
        );

        $roles = $this->getTable('Role')->select()->where($where)->order(array('RoleId' => 'DESC'))->fetchAll()->toArray();

        if (!$this->getService('AuthService')->hasIdentity()) {
            $this->getService('LoggerService')->emergLog('Error Processing Request (No identity defined)');
            throw new Exception("Error Processing Request (No identity defined)", 1);
        }
        $this->getService('LoggerService')->emergLog('Getting inherited Roles)');

        $identity = $this->getService('AuthService')->getIdentity();





        return $roles;
    }
}
