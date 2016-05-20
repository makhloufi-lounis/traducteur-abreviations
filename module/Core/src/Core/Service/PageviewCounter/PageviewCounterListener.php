<?php

namespace Core\Service\PageviewCounter;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PageviewCounterListener implements ListenerAggregateInterface {
	protected $servicesLocator;
	protected $listeners = array ();
	protected $mailSender;
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->servicesLocator = $serviceLocator;
	}
	public function getServiceLocator() {
		return $this->servicesLocator;
	}
	public function attach(EventManagerInterface $events) {
		$this->listeners [] = $events->attach ( 'statistics-actualite.count', array (
				$this,
				'statisticsActualite' 
		), 200 );
		$this->listeners [] = $events->attach ( 'statistics-fiche.count', array (
				$this,
				'statisticsFiche' 
		), 201 );
	}
	public function detach(EventManagerInterface $events) {
		foreach ( $this->listeners as $index => $listener ) {
			if ($events->detach ( $listener )) {
				unset ( $this->listeners [$index] );
			}
		}
	}
	public function statisticsActualite(EventInterface $event) {
		$statisticsAcutaliteCounter = $this->getServiceLocator ()->get ( 'statisticsActualiteCounter' );
		$params = $event->getParams ();
		if (array_key_exists ( "actualite_id", $params )) {
			$actualite_id = $params ["actualite_id"];
		}
		$statisticsAcutaliteCounter->count ( $actualite_id );
	}
	public function statisticsFiche(EventInterface $event) {
		$statisticsAcutaliteCounter = $this->getServiceLocator ()->get ( 'statisticsFicheCounter' );
		$params = $event->getParams ();
		if (array_key_exists ( "fiche_id", $params )) {
			$fiche_id = $params ["fiche_id"];
		}
		$statisticsAcutaliteCounter->count ( $fiche_id );
	}
}