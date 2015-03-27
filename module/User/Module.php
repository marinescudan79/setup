<?php
namespace User;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

class Module implements AutoloaderProviderInterface
{

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $manager      = $e->getApplication()->getServiceManager()->get('Zend\Session\ManagerInterface');
        // $manager->rememberMe($manager->getConfig()->getCookieLifetime());
    }

    public function addLoginOverlay(MvcEvent $event)
    {
        $viewModel = $event->getViewModel();

        $uri = $event->getRequest()->getRequestUri();
        if ($uri != '/login') {
            $loginViewModel = new ViewModel();
            $loginViewModel->setTemplate('/layout/login');
            $loginViewModel->addChild($viewModel, 'content');

            $event->setViewModel($loginViewModel);
        }
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
