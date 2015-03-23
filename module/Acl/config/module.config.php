<?php
return array(
    'router' => array(
        'routes' => array(
            'Acl' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/secure',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Acl\Controller',
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
            'Acl\Controller\Index' => 'Acl\Controller\IndexController',
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
            'ResourceService' => 'Acl\Service\Invokable\ResourceService',
            /*Validators*/
            'ResourceExist'    => 'Acl\Validator\ResourceExist',
        ),
        'aliases' => array(
        ),
        'shared' => array(
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
