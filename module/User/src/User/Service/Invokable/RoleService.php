<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-21 21:24:11
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-29 18:25:53
 */

namespace User\Service\Invokable;

use Application\Utils\Service\AbstractService;

class RoleService extends AbstractService
{
    protected $inheritedRoles = array();

    public function addRole($post)
    {
        $this->getTable('Role')->insert($post);
    }

    public function getRoleName($roleId)
    {
        $result = $this->getTable('Role')->select()->where(array('RoleId' => $roleId))->fetchRow();

        return $result->RoleName;
    }

    public function listRoles($form = false, $showDeleted = false)
    {
        $where = array(
            new \Zend\Db\Sql\Predicate\Expression("Role.Status <> 'Deleted'")
        );

        $join = array(
            array(
                'table_name' => 'Role',
                'join_condition' => 'Role.RoleParentId = RoleParent.RoleId',
                'columns' => array('ParentUserRoleName' => 'RoleName'),
                'join' => 'left',
                'alias' => 'RoleParent'
            ),
        );

        $roles = $this->getTable('Role')->select()->where($where)->join($join)->fetchAll();

        return $roles;
    }

    public function getRoles($role = false, $showDeleted = false)
    {
        $where = array(
            new \Zend\Db\Sql\Predicate\Expression("Role.Status <> 'Deleted'")
        );

        $roleResources = array();

        $roles = $this->getTable('Role')->select()->where($where)->fetchKeyValue(array('RoleId', 'RoleName'));

        return $roles;
    }

    public function getInerhitedRolesResources()
    {
        $roles = $this->getInheritedRoles();

        $roleResources = array();

        foreach ($roles as $roleId => $roleName) {
            $roleResources[$roleName] = $this->getService('ResourceService')->listRoleResources($roleId);
        }
        return $roleResources;
    }

    public function getInheritedRoles($showDeleted = false)
    {

        $where = array(
            new \Zend\Db\Sql\Predicate\Expression("Role.Status <> 'Deleted'")
        );

        $join = array(
            array(
                'table_name' => 'Role',
                'join_condition' => 'Role.RoleParentId = RoleParent.RoleId',
                'columns' => array('ParentUserRoleName' => 'RoleName'),
                'join' => 'left',
                'alias' => 'RoleParent'
            ),
        );

        $roles = $this->getTable('Role')->select()->where($where)->join($join)->order(array('RoleId' => 'DESC'))->fetchAll()->toArray();

        if (!$this->getService('AuthService')->hasIdentity()) {
            $roleId = 'Guest';
        } else {
            $identity = $this->getService('AuthService')->getIdentity();
            $roleId = $identity->RoleName;

            if (isset($identity->IsSuperUser) && $identity->IsSuperUser == 1) {
                $roleId = 'IsSuperUser';
            }
        }


        $this->extractInheritedRoles($roles, $roleId);
        // \Zend\Debug\Debug::dump($roleId);
        return $this->inheritedRoles;
    }

    private function extractInheritedRoles($roles, $roleName = false)
    {
        foreach ($roles as $role) {
            if ($roleName == 'IsSuperUser') {
                $this->inheritedRoles[$role['RoleId']] = $role['RoleName'];
                continue;
            }
            if ($role['RoleName'] == $roleName) {
                $this->inheritedRoles[$role['RoleId']] = $role['RoleName'];
                if (!empty($role['ParentUserRoleName'])) {
                    $this->extractInheritedRoles($roles, $role['ParentUserRoleName']);
                }
            }
        }
    }

    public function getRoleResources()
    {

    }
}
