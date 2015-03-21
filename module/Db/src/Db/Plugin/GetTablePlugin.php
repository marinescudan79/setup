<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-21 12:19:09
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-21 13:37:57
 */

namespace Db\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class GetTablePlugin extends AbstractPlugin
{
    public function __invoke($tableName)
    {
        return $this->getController()->getServiceLocator()->get('getTable')->init($tableName);
    }
}
