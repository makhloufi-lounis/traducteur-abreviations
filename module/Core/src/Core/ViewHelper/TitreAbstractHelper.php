<?php

namespace Core\ViewHelper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class TitreAbstractHelper extends AbstractHelper implements ServiceLocatorAwareInterface {
	protected $serviceLocator;
	public function setServiceLocator(ServiceLocatorInterface $sl) {
		$this->serviceLocator = $sl;
	}
	public function getServiceLocator() {
		return $this->serviceLocator;
	}
	protected function generateParticule($idLocalisation) {
		if (preg_match ( '/^[0-9]+$/', $idLocalisation )) {
			return 'à';
		}
		$particule = '';
		$len = strlen ( $idLocalisation );
		if ($len == 5) {
			if ($idLocalisation == '33_23')
				$particule = "dans les";
			else if ($idLocalisation == "33_07" || $idLocalisation == "33_17" || $idLocalisation == "33_14")
				$particule = "dans le";
			else
				$particule = "en";
		} else if ($len >= 8) {
			$num = substr ( $idLocalisation, 6 );
			$motif = ',' . $num . ',';
			if (strpos ( ',03,61,89,36,10,27,91,11,34,02,60,01,', $motif ) !== false) {
				$particule = "dans l'";
			} elseif (strpos ( ',50,58,51,23,72,80,86,26,42,', $motif ) !== false) {
				$particule = "dans la";
			} elseif (strpos ( ',40,64,22,08,78,92,66,88,65,79,04,05,06,13,', $motif ) !== false) {
				$particule = "dans les";
			} elseif (strpos ( ',75,974,975,976,', $motif ) !== false) {
				$particule = "à";
			} elseif (strpos ( ',67,68,15,63,14,29,56,18,41,45,25,39,90,94,95,30,32,46,81,82,59,62,49,83,69,', $motif ) !== false) {
				$particule = "dans le";
			} else {
				$particule = "en";
			}
		}
		return $particule;
	}
}
