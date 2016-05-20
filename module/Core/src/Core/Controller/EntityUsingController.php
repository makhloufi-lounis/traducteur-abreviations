<?php

namespace Core\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class EntityUsingController extends AbstractActionController {
	protected $entityManager;
	protected $storage;
	protected $authservice;
	protected $config;
	protected $controllerActionName;
	public function getPhpRenderer(){
		return $this->getServiceLocator ()->get ( 'Zend\View\Renderer\PhpRenderer' );
	}
	public function getControllerActionName(){
		return $this->params('controller').'\\'.$this->params('action');
	}
	public function getAuthService() {
		if (! $this->authservice) {
			$this->authservice = $this->getServiceLocator ()->get ( 'AuthService' );
		}
		return $this->authservice;
	}
	public function getConfiguration() {
		if (! $this->config) {
			$this->config = $this->getServiceLocator ()->get ( 'Config' );
		}
		return $this->config;
	}
	public function getSessionStorage() {
		if (! $this->storage) {
			$this->storage = $this->getServiceLocator ()->get ( 'Application\Model\AuthStorage' );
		}
		return $this->storage;
	}

	protected function setEntityManager(\Doctrine\ORM\EntityManager $em) {
		$this->entityManager = $em;
		return $this;
	}
	protected function getEntityManager() {
		if (null === $this->entityManager) {
			$this->setEntityManager ( $this->getServiceLocator ()->get ( 'doctrine.entitymanager.orm_default' ) );
		}
		return $this->entityManager;
	}
	protected function getUtilisateursTable() {
		return $this->getServiceLocator ()->get ( 'utilisateurs_table' );
	}
	protected function getSuiviUtilisateursTable() {
		return $this->getServiceLocator ()->get ( 'suivi_utilisateurs_table' );
	}
	protected function getUtilisateurIdentity(){
		return $this->getAuthService ()->getIdentity ();
	}
}