<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Config\SessionConfig;
use Zend\Session\SessionManager;
use Zend\Session\Container;
use Zend\ModuleManager\ModuleManager;

class Module
{
    /*public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
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
    }*/
	
	public function onBootstrap(MvcEvent $e) {
		$eventManager = $e->getApplication ()->getEventManager ();
		$moduleRouteListener = new ModuleRouteListener ();
		$moduleRouteListener->attach ( $eventManager );
		$eventManager->attach ( 'route', array (
				$this,
				'administrationAclControl'
		), 2 );
		$config = $e->getApplication ()->getServiceManager ()->get ( 'Config' );
		if (php_sapi_name () !== 'cli') {
			$sessionConfig = new SessionConfig ();
			$sessionConfig->setOptions ( $config ['session'] );
			$sessionManager = new SessionManager ( $sessionConfig );
			$sessionManager->start ();
			Container::setDefaultManager ( $sessionManager );
		}
	}
	public function administrationAclControl(MvcEvent $e) {
		$application = $e->getApplication ();
		$sm = $application->getServiceManager ();
		$sharedManager = $application->getEventManager ()->getSharedManager ();
		$router = $sm->get ( 'router' );
		$request = $sm->get ( 'request' );
	
		$matchedRoute = $router->match ( $request );
		if (null !== $matchedRoute) {
			$sharedManager->attach ( 'Zend\Mvc\Controller\AbstractActionController', 'dispatch', function ($e) use($sm) {
				$sm->get ( 'ControllerPluginManager' )->get ( 'AclPlugin' )->doAuthorization ( $e );
			}, 2 );
		}
	}
	public function init(ModuleManager $moduleManager) {
		$sharedEvents = $moduleManager->getEventManager ()->getSharedManager ();
		$sharedEvents->attach ( __NAMESPACE__, 'dispatch', function ($e) {
			$controller = $e->getTarget ();
		}, 100 );
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
