<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-02-22 22:14:47
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-31 15:15:13
 */

namespace Acl\Service\Invokable;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
use Zend\Permissions\Acl\AclInterface;
use Zend\Debug\Debug;
use Zend\Db\Sql\Literal;

use Application\Utils\Service\AbstractService;

class AclService extends AbstractService
{

    protected $acl;

    public function getAcl()
    {
        return $this->acl;
    }

    public function createAcl($userId = false, $roleToCreate = false)
    {
        $acl = new Acl();

        $identity = $this->getService('AuthService')->getIdentity();

        if (empty($identity)) {
            $roleNameUser = 'Guest';
        } else {
            $roleNameUser = $identity->RoleName;
        }


        $roles = $this->getService('RoleService')->listRoles()->toArray();
        $resources = $this->getService('ResourceService')->listResources()->toArray();

        foreach ($resources as $resource) {
            $res = $resource['IsExternalUrl'] == 1 ? $resource['ResourceEntry'] : $this->getRoute($resource['ResourceEntry']);
            $acl->addResource(new Resource($res));
        }

        foreach ($roles as $role) {
            if (empty($role['ParentUserRoleName'])) {
                $acl->addRole(new Role($role['RoleName']));
            } else {
                $acl->addRole(new Role($role['RoleName']), $role['ParentUserRoleName']);
            }
        }

        $personRoles = $this->getService('RoleService')->getInerhitedRolesResources();
        // \Zend\Debug\Debug::dump($personRoles);
        // die;

        $mvcRequest     = new \Zend\Http\Request();
        $mvcRouter      = $this->getServiceLocator()->get('router');


        if (!empty($personRoles)) {
            foreach ($personRoles as $roleName => $modules) {
                foreach ($modules as $module => $controllers) {
                    foreach ($controllers as $controller => $uris) {
                        foreach ($uris as $uri) {
                            $mvcRequest->setUri($uri);
                            $match = $mvcRouter->match($mvcRequest);
                            $resource = strtolower($module).'/'.$match->getParams()['controller'];
                            // \Zend\Debug\Debug::dump($resource .' - '. $match->getParams()['action']);
                            $acl->allow($roleName, $resource, $match->getParams()['action']);
                        }
                    }

                }

            }
        }

        // \Zend\Debug\Debug::dump($this->getService('RoleService')->getInheritedRoles());
        // \Zend\Debug\Debug::dump($acl);
        // die;

        // if ($userId) {
        //     $userResources = $this->getService('PrivilegesProvider')->setUserPrivilege($userId);

        //     if (!empty($userResources)) {
        //         foreach ($userResources as $resource) {
        //             $this->getService('ResourceProvider')->setResources($userResources, 'User');
        //         }
        //     }
        // }


        // $resources = $this->getService('ResourceProvider')->getResources();

        // foreach ($resources as $resource => $privileges) {

        //     if (!isset($privileges['Privilege'])) {
        //         $acl->allow($privileges['Role'], $resource, null);
        //         continue;
        //     }

        //     foreach ($privileges['Privilege'] as $privilege => $type) {
        //         if ($type == 'Allow') {
        //             $acl->allow($privileges['Role'], $resource, $privilege);
        //         } else {
        //             $acl->deny($privileges['Role'], $resource, $privilege);
        //         }
        //     }
        // }


        // $rolePrivileges = $this->getService('PrivilegesProvider')->setRolePrivilege('Employee');

        // \Zend\Debug\Debug::dump($rolePrivileges);
        // die;



        // $acl->addResource(new Resource('application/index'));
        // $acl->allow(null, 'application/index', null);
        // $acl->addResource(new Resource('navigation/index'));
        // $acl->allow(null, 'navigation/index', null);
        // $acl->addResource(new Resource('admin/exportgrouppublicquotes'));
        // $acl->allow(null, 'admin/exportgrouppublicquotes', 'index');
        // $acl->addResource(new Resource('admin/enrollments'));
        // $acl->allow(null, 'admin/enrollments', 'index');


        // $navigation = $this->getService('Zend\View\HelperPluginManager');
        // \Zend\Debug\Debug::dump($acl);
        // die;
        // $navigation->setAcl($acl);



        $this->getServiceLocator()->setAllowOverride(true);
        $this->getServiceLocator()->setService('Zend\Permissions\Acl', $acl);
        // $this->getServiceLocator()->setService('Acl', $acl);
        $this->getServiceLocator()->setAllowOverride(false);
        \Zend\View\Helper\Navigation\AbstractHelper::setDefaultAcl($acl);
        \Zend\View\Helper\Navigation\AbstractHelper::setDefaultRole($roleNameUser);
        return $acl;
    }

    public function getRoute($controller)
    {
        $controller = explode('\\', $controller);
        return strtolower(strtolower($controller[0]).'/'.strtolower($controller[2]));
    }
}
