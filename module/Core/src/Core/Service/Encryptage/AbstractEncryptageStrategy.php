<?php

namespace Core\Service\Encryptage;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class AbstractEncryptageStrategy implements ServiceLocatorAwareInterface, EncryptageStrategyInterface {
	
	protected $serviceLocator;
	public function setServiceLocator(ServiceLocatorInterface $sl) {
		$this->serviceLocator = $sl;
	}
	public function getServiceLocator() {
		return $this->serviceLocator;
	}
}