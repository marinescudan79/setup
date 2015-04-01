<?php

namespace Navigation;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface
{

    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getServiceManager()->get('NavigationService')->getNavigationItems('MenuNavigation');
    }
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
