<?php

namespace Application\Controller\Plugin;

use Core\Entity\User;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\MvcEvent;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\GenericResource;
use Zend\Permissions\Acl\Role\GenericRole as Role;

class AclPlugin extends AbstractPlugin {
	private $moduleName;
	private $controllerName;
	private $actionName;
	private $initialized;
	private $config;
	public function __construct() {
		$this->initialized = false;
	}
	private function initialize(MvcEvent $e) {
		$controller = $e->getTarget ();
		$controllerClass = get_class ( $controller );
		$this->moduleName = strtolower ( substr ( $controllerClass, 0, strpos ( $controllerClass, '\\' ) ) );
		$routeMatch = $e->getRouteMatch ();
		$this->actionName = strtolower ( $routeMatch->getParam ( 'action', 'not-found' ) );
		$controllerName = $routeMatch->getParam ( 'controller', 'not-found' );
		$controllerName = explode ( '\\', $controllerName );
		$controllerName = array_pop ( $controllerName );
		$this->controllerName = strtolower ( $controllerName );
		$this->config = $e->getApplication ()->getServiceManager ()->get ( 'Config' );
		$this->initialized = true;
	}
	public function doAuthorization(MvcEvent $e) {
		if ($this->initialized === false) {
			$this->initialize ( $e );
		}
		if ($this->moduleName == 'application') {

			$acl = new Acl ();
			
			$aclList = $this->config ['aclList'];
			foreach ( $aclList as $role => $privileges ) {
				$acl->addRole ( new Role ( $role ) );
				if ($privileges ['allow'] == "all") {
					$acl->allow ( $role );
					if (! empty ( $privileges ['deny'] )) {
						foreach ( $privileges ['deny'] as $controller => $actions ) {
							if ($actions != "all" && is_array ( $actions )) {
								foreach ( $actions as $action ) {
									if (! $acl->hasResource ( $controller ))
										$acl->addResource ( new GenericResource ( $controller, $action ) );
									$acl->deny ( $role, $controller, $action );
								}
							} else {
								if (! $acl->hasResource ( $controller ))
									$acl->addResource ( new GenericResource ( $controller ) );
								$acl->deny ( $role, $controller );
							}
						}
					}
				} elseif ($privileges ['deny'] == "all") {
					$acl->deny ( $role );
					if (! empty ( $privileges ['allow'] )) {
						foreach ( $privileges ['allow'] as $controller => $actions ) {
							if ($actions != "all" && is_array ( $actions )) {
								foreach ( $actions as $action ) {
									if (! $acl->hasResource ( $controller ))
										$acl->addResource ( new GenericResource ( $controller ) );
									$acl->allow ( $role, $controller, $action );
								}
							} else {
								if (! $acl->hasResource ( $controller ))
									$acl->addResource ( new GenericResource ( $controller ) );
								$acl->allow ( $role, $controller );
							}
						}
					}
				}
			}
			
			$auth = $e->getApplication ()->getServiceManager ()->get ( 'zfcuser_auth_service' );
			$roleCurrent = ($auth->getIdentity () == null) ? User::ROLE_GUEST : $auth->getIdentity ()->getRole ();
			if (! $acl->hasResource ( $this->controllerName )) {
				$acl->addResource ( $this->controllerName );
			}
			if (! $acl->isAllowed ( $roleCurrent, $this->controllerName, $this->actionName )) {
				$router = $e->getRouter ();
				$url = $router->assemble ( array (), array (
						'name' => 'admin' 
				) );
				$response = $e->getResponse ();
				$response->setStatusCode ( 302 );
				$response->getHeaders ()->addHeaderLine ( 'Location', $url . '?from=2' );
				$response->sendHeaders ();
				$stopCallBack = function ($event) use($response) {
					$event->stopPropagation ();
					return $response;
				};
				$e->getApplication ()->getEventManager ()->attach ( MvcEvent::EVENT_ROUTE, $stopCallBack, - 10000 );
				return $response;
			}
		}
	}
}
