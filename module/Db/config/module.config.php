<?php
return array(
    'router' => array(
        'routes' => array(
            'db' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Db\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'db' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/db',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Db\Controller',
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
    'controller_plugins' => array(
        'invokables' => array(
            'getTable'    => 'Db\Plugin\GetTablePlugin',
        )
    ),
    'service_manager' => array(
        'abstract_factories' => array(
        ),
        'invokables' => array(
            'Db\Service\Invokable\AbstractTable' => 'Db\Service\Invokable\AbstractTable',
        ),
        'aliases' => array(
            'getTable' => 'Db\Service\Invokable\AbstractTable',
            'adapter'  => 'Zend\Db\Adapter\Adapter',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Db\Controller\Index' => 'Db\Controller\IndexController'
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
