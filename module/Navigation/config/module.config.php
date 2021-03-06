<?php
return array(
    'router' => array(
        'routes' => array(
            'navigation' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/navigation',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Navigation\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action][/id/:id][/page/:page][/order_by/:order_by][/:order]]',
                            'constraints' => array(
                                'controller'    =>  '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'        =>  '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'            =>  '[a-zA-Z0-9_-]+',
                                'page'          =>  '[0-9]+',
                                'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'order' => 'ASC|DESC',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Navigation\Controller\Index' => 'Navigation\Controller\IndexController',
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
        )
    ),
    'service_manager' => array(
        'abstract_factories' => array(
        ),
        'invokables' => array(
            'NavigationService' => 'Navigation\Service\Invokable\NavigationService',
        ),
        'aliases' => array(
        ),
        'shared' => array(
        ),
         'factories' => array(
             'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
         ),
    ),
    'navigation' => array(
        'default' => array(
             array(
                 'label' => 'Home',
                 'route' => 'home',
             ),
             array(
                 'label' => 'Users',
                 'route' => 'user/default',
             ),
         ),
    ),
    'view_manager' => array(
        'template_map' => array(
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
