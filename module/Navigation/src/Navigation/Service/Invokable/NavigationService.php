<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-29 19:35:12
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-29 19:46:51
 */

namespace Navigation\Service\Invokable;

use Application\Utils\Service\AbstractService;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Predicate\In;

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

        return $this->getTable('Navigation')->select()->where($where)->join($join)->fetchAll()->toArray();
    }
}
