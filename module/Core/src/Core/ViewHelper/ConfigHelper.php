<?php

namespace Core\ViewHelper;


class ConfigHelper extends TitreAbstractHelper {
	
	
	protected $contact;
	
	const DNS_FUSACQ = 'dns_fusacq';
	const DNS_PAYMENT_FUSACQ = 'dns_payment_fusacq';
	const DNS_PLACEDESCOMMERCES = 'dns_placedescommerces';
	const DNS_PLACEDESFRANCHISES = 'dns_placedesfranchises';
	const EMAIL_DEVELOPPEUR = 'email_developpeur';
	const CDN_PLACEDESFRANCHISES = 'cdn_placedesfranchises';
	const CDN_TRADUCTEUR = 'cdn_traducteur';
	public function __invoke($type) {
		$this->type = $type;
		return $this->generatePartial ();
	}
	private function generatePartial() {
		$config = $this->getServiceLocator ()->getServiceLocator ()->get ( 'Config' );
		switch ($this->type) {
			case self::DNS_FUSACQ :
				return $config [self::DNS_FUSACQ];
				break;
			case self::DNS_PAYMENT_FUSACQ :
				return $config [self::DNS_PAYMENT_FUSACQ];
				break;
			case self::DNS_PLACEDESFRANCHISES :
				return $config [self::DNS_PLACEDESFRANCHISES];
				break;
			case self::DNS_PLACEDESCOMMERCES :
				return $config [self::DNS_PLACEDESCOMMERCES];
				break;
			case self::EMAIL_DEVELOPPEUR :
				return $config [self::EMAIL_DEVELOPPEUR];
				break;
			case self::CDN_PLACEDESFRANCHISES :
				return $config [self::CDN_PLACEDESFRANCHISES];
				break;
			case self::CDN_TRADUCTEUR :
				return $config [self::CDN_TRADUCTEUR];
				break;
			default :
				return "";
				break;
		}
	}
	
	
}
