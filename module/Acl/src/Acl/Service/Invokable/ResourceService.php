<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-23 00:30:13
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-28 00:29:31
 */

namespace Acl\Service\Invokable;

use Application\Utils\Service\AbstractService;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Predicate\In;

class ResourceService extends AbstractService
{
    public function listResources($paginator = false, $showDeleted = false)
    {
        $where = array();
        if (!$showDeleted) {
            $where[] = new Expression("Status <> 'Deleted'");
        }
        return $this->getTable('Resource')->select()->where($where)->fetchAll();
    }

    public function saveRoleResources($roleId, $savedResources)
    {
        $currentResources = $this->listRoleResources($roleId);

        $diffToAdd = $this->rolePrivilegesDiff($savedResources, $currentResources);
        $diffToRemove = $this->rolePrivilegesDiff($currentResources, $savedResources);

        if (!empty($diffToAdd)) {
            $this->addRemoveRoleResources($diffToAdd, $roleId);
        }

        if (!empty($diffToRemove)) {
            $this->addRemoveRoleResources($diffToRemove, $roleId, false);
        }
    }

    private function addRemoveRoleResources($resources, $roleId, $add = true)
    {

        $mvcRequest     = new \Zend\Http\Request();
        $mvcRouter      = $this->getServiceLocator()->get('router');
        $resourceData = false;
        foreach ($resources as $resource => $privileges) {
            foreach ($privileges as $resourceEntry => $privileges) {

                $where = array(
                    'ResourceEntry' => $resourceEntry,
                    'Status' => 'Active',
                );
                $resourceData = $this->getTable('Resource')->select()->where($where)->fetchRow();

                $where = array(
                    'RoleId' => $roleId,
                    'ResourceId' => $resourceData->ResourceId,
                    'Status' => 'Active',
                );
                $roleResource = $this->getTable('RoleResource')->select()->where($where)->fetchRow();

                if ($add) {
                    if ($roleResource) {
                        $roleResourceId = $roleResource->RoleResourceId;
                    } else {
                        $roleResourceId = $this->getTable('RoleResource')->insert(array('RoleId' => $roleId, 'ResourceId' => $resourceData->ResourceId));
                    }

                    foreach ($privileges as $privilege) {
                        $mvcRequest->setUri($privilege);
                        $match = $mvcRouter->match($mvcRequest);
                        $this->getTable('RoleResourcePrivilege')->insert(array('RoleResourceId' => $roleResourceId, 'Privilege' => $match->getParams()['action']));
                    }
                } else {
                    $deletePrivileges = array();

                    foreach ($privileges as $privilege) {
                        $mvcRequest->setUri($privilege);
                        $match = $mvcRouter->match($mvcRequest);
                        $deletePrivileges[] = $match->getParams()['action'];
                    }

                    $where = array(
                        'RoleResourceId' => $roleResource->RoleResourceId,
                        new In('Privilege', $deletePrivileges),
                    );
                    $this->getTable('RoleResourcePrivilege')->delete($where);
                    $where = array(
                        'Status' => 'Active',
                        'RoleResourceId' => $roleResource->RoleResourceId,
                    );
                    $havePrivileges = $this->getTable('RoleResourcePrivilege')->select()->where($where)->fetchRow();
                    if (!$havePrivileges) {
                        $this->getTable('RoleResource')->delete(array('RoleResourceId' => $roleResource->RoleResourceId));
                    }
                }
            }
        }
    }

    public function listRoleResources($roleId)
    {
        $join = array(
            array(
                'table_name' => 'RoleResourcePrivilege',
                'join_condition' => new Expression("RoleResource.RoleResourceId = RoleResourcePrivilege.RoleResourceId AND RoleResourcePrivilege.Status <> 'Deleted'"),
                'columns' => 'Privilege'
            ),
            array(
                'table_name' => 'Resource',
                'join_condition' => new Expression("RoleResource.ResourceId = Resource.ResourceId AND Resource.Status <> 'Deleted'"),
                'columns' => 'ResourceEntry'
            ),
        );

        $where = array(
            'RoleId' => $roleId,
            new Expression("RoleResource.Status <> 'Deleted'"),
        );

        $resources = $this->getTable('RoleResource')->select()->where($where)->join($join)->fetchAll()->toArray();

        $list      = array();
        $mvcRouter = $this->getServiceLocator()->get('router');

        foreach ($resources as $resource) {
            $parts = explode('\\', $resource['ResourceEntry']);
            $module = $parts[0];
            $routeMatchParams['controller'] = strtolower(end($parts));
            $routeMatchParams['action']     = $resource['Privilege'];
            $options['name']                = strtolower($module).'/default';

            $list[$module][$resource['ResourceEntry']][] = $mvcRouter->assemble($routeMatchParams, $options);
        }

        return $list;

    }

    public function listModuleResources($showDeleted = false)
    {
        $where = array(
            'IsExternalUrl' => '0',
        );
        if (!$showDeleted) {
            $where[] = new Expression("Status <> 'Deleted'");
        }
        $resources = $this->getTable('Resource')->select()->where($where)->fetchAll();

        $list      = array();
        $mvcRouter = $this->getServiceLocator()->get('router');

        foreach ($resources as $resource) {
            $parts = explode('\\', $resource->ResourceEntry);
            $module = $parts[0];
            $privileges = array();
            $className = $resource->ResourceEntry.'Controller';
            $class = new \ReflectionClass($className);
            $classMethods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
            foreach ($classMethods as $method) {
                if ($className == $method->class) {
                    if (strpos($method->name, 'Action') !== false) {
                        $priv = str_replace('-action', '', strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $method->name)));
                        $routeMatchParams['controller'] = strtolower(end($parts));
                        $routeMatchParams['action']     = $priv;
                        $options['name']                = strtolower($module).'/default';
                        $privileges[] = $mvcRouter->assemble($routeMatchParams, $options);
                    }
                }
            }
            $list[$module][$resource->ResourceEntry] = $privileges;
        }

        return $list;

    }

    public function rolePrivilegesDiff($aArray1, $aArray2)
    {
        $aReturn = array();

        foreach ($aArray1 as $mKey => $mValue) {
            if (array_key_exists($mKey, $aArray2)) {
                if (is_array($mValue)) {
                    $aRecursiveDiff = $this->rolePrivilegesDiff($mValue, $aArray2[$mKey]);
                    if (count($aRecursiveDiff)) {
                        $aReturn[$mKey] = $aRecursiveDiff;
                    }
                } else {
                    if ($mValue != $aArray2[$mKey]) {
                        $aReturn[$mKey] = $mValue;
                    }
                }
            } else {
                $aReturn[$mKey] = $mValue;
            }
        }
        return $aReturn;
    }
}
