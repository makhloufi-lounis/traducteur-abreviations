<?php

namespace Core\Service\Encryptage;

use Zend\Crypt\Password\Bcrypt;

class ZfcUserPasswordEncrytageStrategy extends AbstractEncryptageStrategy {
	public function encryptage($to_be_encrypted) {
		$config = $this->getServiceLocator ()->get ( 'Configuration' );
		$cast = $config ['zfcuser'] ['password_cost'];
		$bcrypt = new Bcrypt ();
		$bcrypt->setCost ( ( int ) $cast );
		$already_encrypted = $bcrypt->create ( $to_be_encrypted );
		return $already_encrypted;
	}
	public function decryptage($to_be_decrypted) {
	}
}