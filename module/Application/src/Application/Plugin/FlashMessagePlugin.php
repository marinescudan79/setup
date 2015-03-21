<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-21 04:22:33
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-21 04:24:56
 */

namespace Application\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class FlashMessagePlugin extends AbstractPlugin
{
    public function __invoke()
    {
        return $this->getController()->getServiceLocator()
                            ->get('ControllerPluginManager')
                            ->get('flashmessenger');
    }
}
