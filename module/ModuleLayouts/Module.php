<?php

namespace ModuleLayouts;

class Module {
	public function onBootstrap($e) {
		$e->getApplication ()->getEventManager ()->getSharedManager ()->attach ( 'Zend\Mvc\Controller\AbstractController', 'dispatch', function ($e) {
			$controller = $e->getTarget ();
			$controllerClass = get_class ( $controller );
			$moduleNamespace = substr ( $controllerClass, 0, strpos ( $controllerClass, '\\' ) );
			$config = $e->getApplication ()->getServiceManager ()->get ( 'config' );
			switch ($moduleNamespace) {
				case 'Application' :
					if ($e->getApplication ()->getServiceManager ()->get ( 'zfcuser_auth_service' )->hasIdentity ()) {
						$controller->layout ( $config ['module_layouts'] [$moduleNamespace] );
					} else {
						$controller->layout ( 'layout/authentication.phtml' );
					}
					break;
				default :
					$controller->layout ( $config ['module_layouts'] ['Core'] );
					break;
			}
		}, 100 );
	}
}
