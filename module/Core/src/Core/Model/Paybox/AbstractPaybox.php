<?php

namespace Core\Model\Paybox;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class AbstractPaybox implements ServiceLocatorAwareInterface {
	protected $serviceLocator;
	public function setServiceLocator(ServiceLocatorInterface $sl) {
		$this->serviceLocator = $sl;
	}
	public function getServiceLocator() {
		return $this->serviceLocator;
	}
}