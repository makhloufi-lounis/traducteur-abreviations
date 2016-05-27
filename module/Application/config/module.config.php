<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

return array(
	'doctrine' => array (
				'driver' => array (
						// overriding zfc-user-doctrine-orm's config
						'zfcuser_entity' => array (
								'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
								'paths' => __DIR__ . '/../../Core/src/Core/Entity' 
						),
						
						'orm_default' => array (
								'drivers' => array (
										'Core\Entity' => 'zfcuser_entity' 
								) 
						) 
				) 
	),
		
	'zfcuser' => array (
				// telling ZfcUser to use our own class
				'user_entity_class' => 'Core\Entity\User',
				// telling ZfcUserDoctrineORM to skip the entities it defines
				'enable_default_entities' => false 
	),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                	'usermanagement' => array (
                		'type' => 'segment',
                		'options' => array (
                			'route' => '/usermanagement[/:action][/:id]',
                			'constraints' => array (
                					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                					'id' => '[0-9]+'
                			),
                			'defaults' => array (
                					'controller' => 'Application\Controller\UserManagement',
                					'action' => 'index'
                			)
                		)
                	),
                    
                ),
            ),
        	'addabreviation' => array(
        			'type'    => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/addabreviation.html',
        					'defaults' => array(
        							'controller'    => 'Application\Controller\Abreviation',
        							'action'        => 'add',
        					),
        				),
        	),
        	'register' => array(
        				'type'    => 'Zend\Mvc\Router\Http\Literal',
        				'options' => array(
        						'route'    => '/user/register',
        						'defaults' => array(
        								'controller' => 'zfc-user\Controller\User',
        								'action'     => '/register',
        						),
        				),
        	),

        ),
    ),
    'service_manager' => array (
				'abstract_factories' => array (
						'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
						'Zend\Log\LoggerAbstractServiceFactory' 
				),
				'aliases' => array (
						'translator' => 'MvcTranslator' 
				),
				'invokables' => array () 
	),		
	'translator' => array (
				'locale' => 'fr_FR',
				'translation_file_patterns' => array (
						array (
								'type' => 'gettext',
								'base_dir' => __DIR__ . '/../language',
								'pattern' => '%s.mo' 
						) 
				) 
	),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => Controller\IndexController::class,
            'Application\Controller\UserManagement' => 'Application\Controller\UserManagementController',
        	'Application\Controller\Abreviation' => 'Application\Controller\AbreviationController',
        	'Application\Controller\Erreur' => 'Application\Controller\ErreurController',
            'Application\Controller\AjaxUsers' => 'Application\Controller\AjaxUsersController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
	'module_layouts' => array (
				'Application' => 'layout/accueil'
	),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
	'controller_plugins' => array (
				'invokables' => array (
						'AclPlugin' => 'Application\Controller\Plugin\AclPlugin'
				)
	),
	'view_helpers' => array (
				'invokables' => array (
						'numberStaticsHelper' => 'Application\ViewHelper\NumberStaticsHelper',
						'adminPaginationHelper' => 'Application\ViewHelper\AdminPaginationHelper'
				)
	)
);
