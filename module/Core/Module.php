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
use Core\Model\Abreviation;
use Core\Model\AbreviationTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;


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
	
	public function getServiceConfig() {
		return array (
				'factories' => array (
						'Core\Model\AbreviationTable' => function ($sm) {
						$tableGateway = $sm->get ( 'AbreviationTableGateway' );
						$table = new AbreviationTable ( $tableGateway );
						return $table;
						},
						'AbreviationTableGateway' => function ($sm) {
						$dbAdapter = $sm->get ( 'dbDictionnaire' );
						$resultSetPrototype = new ResultSet ();
						$resultSetPrototype->setArrayObjectPrototype ( new Abreviation () );
						return new TableGateway ( 'abreviations', $dbAdapter, null, $resultSetPrototype );
						}
						)
				);
	}
}
