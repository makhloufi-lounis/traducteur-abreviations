<?php

namespace Core\Service\ChainFilter;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class AbstractChainFilterStrategy implements ServiceLocatorAwareInterface, ChainFilterStrategyInterface {
	
	protected $serviceLocator;
	public function setServiceLocator(ServiceLocatorInterface $sl) {
		$this->serviceLocator = $sl;
	}
	public function getServiceLocator() {
		return $this->serviceLocator;
	}
}