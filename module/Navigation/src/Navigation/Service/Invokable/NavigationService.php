<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-29 19:35:12
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-31 16:26:54
 */

namespace Navigation\Service\Invokable;

use Application\Utils\Service\AbstractService;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Predicate\In;
use Zend\Navigation;
use Zend\Navigation\Container;

class NavigationService extends AbstractService
{
    public function getNavigationItems($navigationName)
    {
        $where = array(
            'Navigation.NavigationName' => $navigationName,
            'Navigation.Status' => 'Active',
        );

        $join = array(
            array(
                'table_name' => 'NavigationItem',
                'join_condition' => new Expression("Navigation.NavigationId = NavigationItem.NavigationId AND NavigationItem.Status <> 'Deleted'"),
                'columns' => array('NavigationPrivilege'),
            ),
            array(
                'table_name' => 'Resource',
                'join_condition' => new Expression("NavigationItem.ResourceId = Resource.ResourceId AND Resource.Status <> 'Deleted'"),
                'columns' => array('ResourceName', 'ResourceEntry'),
            ),
        );

        $navigationItems = $this->getTable('Navigation')->select()->where($where)->join($join)->fetchAll()->toArray();

        $this->createNavigation($navigationName, $navigationItems);
    }

    private function createNavigation($navigationName, $navigationItems)
    {

        $mvcRouter = $this->getService('router');
        $mvcRouteMatch = $this->getService('request');

        $container = new Navigation\Navigation(array());

        $pages = array();

        if (!empty($navigationItems)) {
            foreach ($navigationItems as $item) {


                $parts = explode('\\', $item['ResourceEntry']);
                $module = $parts[0];

                $pages[] = Navigation\Page\AbstractPage::factory(array(
                    'type'          => 'mvc',
                    'useRouteMatch' => true,
                    'routeMatch'    => $mvcRouter->match($mvcRouteMatch),
                    'resource'      => $this->getService('AclService')->getRoute($item['ResourceEntry']),
                    'privilege'     => $item['NavigationPrivilege'],
                    'label'         => $item['ResourceName'],
                    'controller'    => strtolower(end($parts)),
                    'action'        => $item['NavigationPrivilege'],
                    'router'        => $mvcRouter,
                    'route'         => strtolower($module).'/default',
                ));

                // \Zend\Debug\Debug::dump($mvcRouter->match($mvcRouteMatch));


                // $parts = explode('\\', $item['ResourceEntry']);
                // $module = $parts[0];
                // $routeMatchParams['controller'] = strtolower(end($parts));
                // $routeMatchParams['action']     = $item['NavigationPrivilege'];
                // $options['name']                = strtolower($module).'/default';

                // // $pages[] = $mvcRouter->assemble($routeMatchParams, $options);

                // $pages[] = Navigation\Page\AbstractPage::factory(array(
                //     'type'          => 'uri',
                //     'useRouteMatch' => true,
                //     'resource'      => $this->getService('AclService')->getRoute($item['ResourceEntry']),
                //     'privilege'     => $item['NavigationPrivilege'],
                //     'label'         => $item['ResourceName'],
                //     'uri'           => $mvcRouter->assemble($routeMatchParams, $options),
                // ));
            }
        }
        // $container->addPages($pages);

        $this->getService('navigation')->setPages($pages);

    }
}
