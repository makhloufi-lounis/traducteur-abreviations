<?php

namespace Core\ViewHelper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class StaticsAbstractHelper extends AbstractHelper implements ServiceLocatorAwareInterface {
	protected $serviceLocator;
	public function setServiceLocator(ServiceLocatorInterface $sl) {
		$this->serviceLocator = $sl;
	}
	public function getServiceLocator() {
		return $this->serviceLocator;
	}
	public function getEntityManager(){
		return $this->getServiceLocator ()->getServiceLocator ()->get ( 'doctrine.entitymanager.orm_default' );
	}
	public function getUtilisateursTable(){
		return $this->getServiceLocator ()->getServiceLocator ()->get ( 'utilisateurs_table' );
	}
}
