<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Core;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module {
	public function onBootstrap(MvcEvent $e) {
		$eventManager = $e->getApplication ()->getEventManager ();
		$moduleRouteListener = new ModuleRouteListener ();
		$moduleRouteListener->attach ( $eventManager );
		$sm = $e->getApplication ()->getServiceManager ();
		$traducteurEventManager = $sm->get ( "traducteur_event_manager" );
		$pageviewCounter = $sm->get ( "pageviewCounter" );
		$traducteurEventManager->attachAggregate ( $pageviewCounter );
		$em = $sm->get ( 'doctrine.entitymanager.orm_default' );
		$platform = $em->getConnection ()->getDatabasePlatform ();
		$platform->registerDoctrineTypeMapping ( 'bit', 'string' );
	}
	public function getConfig() {
		return include __DIR__ . '/config/module.config.php';
	}
	public function getAutoloaderConfig() {
		return array (
				'Zend\Loader\StandardAutoloader' => array (
						'namespaces' => array (
								__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__ 
						) 
				) 
		);
	}
}
