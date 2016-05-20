<?php
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Core\Model\Utilisateurs;
use Core\Model\UtilisateursTable;
use Core\Model\SuiviUtilisateurs;
use Core\Model\SuiviUtilisateursTable;
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */
return array (
		'service_manager' => array (
				'abstract_factories' => array (
						'Zend\Cache\Service\StorageCacheAbstractServiceFactory' 
				),
				'factories' => array (
						'Core\Cache' => 'Zend\Cache\Service\StorageCacheFactory' 
				),
				'aliases' => array (
						'zfc-user-password-encryptage' => 'Core\Service\Encryptage\ZfcUserPasswordEncrytageStrategy',
						'aes-encryptage' => 'Core\Service\Encryptage\AesEncryptageStrategy',
						'contact-id-encryptage' => 'Core\Service\Encryptage\ContactIdEncryptageStrategy',
						'replace-accents-filter' => 'Core\Service\ChainFilter\ReplaceAccentsStrategy',
						'seo-url-filter' => 'Core\Service\ChainFilter\SeoUrlStrategy',
						'euros-exchange-filter' => 'Core\Service\ChainFilter\ExchangeEurosStrategy',
						'random-password-generator' => 'Core\Service\RandomPasswordGenerator',
						'pageviewCounter' => 'Core\Service\PageviewCounter\PageviewCounterListener',
						'traducteur_event_manager' => 'Core\Service\EventManager\TraducteurEventManager',
				),
				'invokables' => array (
						'Core\Service\Encryptage\ZfcUserPasswordEncrytageStrategy' => 'Core\Service\Encryptage\ZfcUserPasswordEncrytageStrategy',
						'Core\Service\Encryptage\AesEncryptageStrategy' => 'Core\Service\Encryptage\AesEncrytageStrategy',
						'Core\Service\Encryptage\ContactIdEncryptageStrategy' => 'Core\Service\Encryptage\ContactIdEncrytageStrategy',
						'Core\Service\ChainFilter\ReplaceAccentsStrategy' => 'Core\Service\ChainFilter\ReplaceAccentsStrategy',
						'Core\Service\ChainFilter\SeoUrlStrategy' => 'Core\Service\ChainFilter\SeoUrlStrategy',
						'Core\Service\ChainFilter\ExchangeEurosStrategy' => 'Core\Service\ChainFilter\ExchangeEurosStrategy',
						'Core\Service\RandomPasswordGenerator' => 'Core\Service\RandomPasswordGenerator',
						'Core\Service\PageviewCounter\PageviewCounterListener' => 'Core\Service\PageviewCounter\PageviewCounterListener',
						'Core\Service\EventManager\TraducteurEventManager' => 'Core\Service\EventManager\TraducteurEventManager' 
				) 
		),
		'controller_plugins' => array (
				'invokables' => array () 
		),
		'view_manager' => array (
				'display_not_found_reason' => (APPLICATION_ENV == 'development'),
				'display_exceptions' => (APPLICATION_ENV == 'development'),
				'doctype' => 'HTML5',
				'not_found_template' => 'error/404',
				'exception_template' => 'error/index',
				'template_map' => array (
						'error/404' => __DIR__ . '/../view/error/404.phtml',
						'error/index' => __DIR__ . '/../view/error/index.phtml' 
				),
				'template_path_stack' => array (
						__DIR__ . '/../view' 
				),
				'strategies' => array (
						'ViewJsonStrategy' 
				) 
		),
		
		'module_layouts' => array (
				'Core' => 'layout/layout' 
		),
		'cache' => array (
				'adapter' => array (
						'name' => 'filesystem' 
				),
				'options' => array (
						'cache_dir' => 'data/cache/' 
				) 
		),
		'view_helpers' => array (
				'invokables' => array (
						'iconHelper' => 'Core\ViewHelper\IconHelper',
						'configHelper' => 'Core\ViewHelper\ConfigHelper' 
				) 
		),
		'webservices' => array (
				//
		),
		'console' => array (
				
		),
		'controllers' => array (
				
		) 
);
