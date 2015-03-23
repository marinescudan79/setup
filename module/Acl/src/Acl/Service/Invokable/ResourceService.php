<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-23 00:30:13
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-23 02:02:22
 */

namespace Acl\Service\Invokable;

use Application\Utils\Service\AbstractService;

class ResourceService extends AbstractService
{
    public function listResources($paginator = false, $showDeleted = false)
    {
        $where = array();
        if (!$showDeleted) {
            $where[] = new \Zend\Db\Sql\Predicate\Expression("Status <> 'Deleted'");
        }
        return $this->getTable('Resource')->select()->where($where)->fetchAll();
    }
    public function addResource($post)
    {
        return $this->getTable('Resource')->insert($post);
    }
}
