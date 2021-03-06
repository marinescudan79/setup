<?php

namespace Acl;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

class Module implements AutoloaderProviderInterface
{

    public function onBootstrap(MvcEvent $e)
    {
        $this->services = $e->getApplication()->getServiceManager();

        $identity = $this->services->get('AuthService')->getIdentity();

        if (empty($identity)) {
            $this->roleName = 'Guest';
        } else {
            $this->roleName = $identity->RoleName;
        }


        $this->acl = $this->services->get('AclService')->createAcl();


        $em = $e->getApplication()->getEventManager();
        if (!$e->getApplication()->getRequest() instanceof ConsoleRequest) {
            $em->attach(MvcEvent::EVENT_DISPATCH, array($this, 'onDispatch'));
        }

        // \Zend\Debug\Debug::dump($acl);
    }

    public function onDispatch(MvcEvent $e)
    {

        $params = $e->getRouteMatch()->getParams();
        $resource = $this->services->get('AclService')->getRoute($params['controller']);
        if (!$this->acl->hasResource($resource)) {
            $e->getResponse()->setStatusCode(404);
            return;
        }
        $allowed = $this->services->get('Zend\Permissions\Acl')->isAllowed($this->roleName, $resource, $params['action']);
        // \Zend\Debug\Debug::dump($this->roleName);
        // \Zend\Debug\Debug::dump($resource);
        // \Zend\Debug\Debug::dump($allowed);
        if (!$allowed) {
            $response = $e->getResponse();
            $model    = new ViewModel();
            $response = $response ?: new HttpResponse();
            $model->setTemplate('error/403');
            $e->getViewModel()->addChild($model);
            $response->setStatusCode(403);
            $e->setResponse($response);
            return;
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
