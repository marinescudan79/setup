<?php
return array(
    'router' => array(
        'routes' => array(
            'login' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/login',
                    'defaults' => array(
                        'controller' => 'User\Controller\Auth',
                        'action'     => 'login',
                    ),
                ),
            ),
            'logout' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/logout',
                    'defaults' => array(
                        'controller' => 'User\Controller\Auth',
                        'action'     => 'logout',
                    ),
                ),
            ),
            'authenticate' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/authenticate',
                    'defaults' => array(
                        'controller' => 'User\Controller\Auth',
                        'action'     => 'authenticate',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'user' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/user',
                    'defaults' => array(
                        '__NAMESPACE__' => 'User\Controller',
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
            'User\Controller\Role'  => 'User\Controller\RoleController',
            'User\Controller\Index' => 'User\Controller\IndexController',
            'User\Controller\Auth'  => 'User\Controller\AuthController'
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'getAuthService'    => 'User\Plugin\AuthServicePlugin',
            'getSessionStorage' => 'User\Plugin\AuthStoragePlugin',
            'getIdentity'       => 'User\Plugin\GetIdentityPlugin',
            'hasIdentity'       => 'User\Plugin\HasIdentityPlugin',
        )
    ),
    'service_manager' => array(
        'invokables' => array(
            'AuthService'  => 'Zend\Authentication\AuthenticationService',
            'UserService'  => 'User\Service\Invokable\UserService',
            'RoleService'  => 'User\Service\Invokable\RoleService',
            /*Validators*/
            'UserExist'    => 'User\Service\Validator\UsernameExist',
            'UserNotExist' => 'User\Service\Validator\UsernameNotExist',
            'EmailExist'   => 'User\Service\Validator\EmailExist',
        ),
        'services' => array(
            'PasswordService' => new \User\Service\PasswordService(),
            'AuthStorage'     => new \User\Service\AuthStorage(),
        ),
        'abstract_factories' => array(
        ),
        'factories' => array(
            'Zend\Session\ManagerInterface' => 'Zend\Session\Service\SessionManagerFactory',
            'Zend\Session\Config\ConfigInterface' => 'Zend\Session\Service\SessionConfigFactory',
        ),
        'aliases' => array(
            'Zend\Authentication\AuthenticationService' => 'AuthService'
        ),
    ),
    'session_config' => array(
        'cache_expire' => 6,
        'cookie_lifetime' => 6,
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
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
