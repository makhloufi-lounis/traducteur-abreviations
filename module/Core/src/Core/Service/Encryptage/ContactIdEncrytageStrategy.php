<?php

namespace Core\Service\Encryptage;



class ContactIdEncrytageStrategy extends AbstractEncryptageStrategy {
	private $keyEncrypt;
	private $initialized = false;
	private function initialize() {
		$config = $this->getServiceLocator ()->get ( 'Configuration' );
		$this->keyEncrypt = $config ['contact_id_encrypt_key'];
		$this->initialized = true;
	}
	private function mysql_aes_key($key) {
		$new_key = str_repeat ( chr ( 0 ), 16 );
		for($i = 0, $len = strlen ( $key ); $i < $len; $i ++) {
			$new_key [$i % 16] = $new_key [$i % 16] ^ $key [$i];
		}
		return $new_key;
	}
	public function encryptage($to_be_encrypted) {
		if (false===$this->initialized) {
			$this->initialize();
		}
		$key = $this->keyEncrypt;
		$key = $this->mysql_aes_key ( $key );
		$pad_value = 16 - (strlen ( $to_be_encrypted ) % 16);
		$to_be_encrypted = str_pad ( $to_be_encrypted, (16 * (floor ( strlen ( $to_be_encrypted ) / 16 ) + 1)), chr ( $pad_value ) );
		return strtoupper ( bin2hex ( mcrypt_encrypt ( MCRYPT_RIJNDAEL_128, $key, $to_be_encrypted, MCRYPT_MODE_ECB, mcrypt_create_iv ( mcrypt_get_iv_size ( MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB ), MCRYPT_DEV_URANDOM ) ) ) );
	}
	public function decryptage($to_be_decrypted) {
		if (false===$this->initialized) {
			$this->initialize();
		}
		$key = $this->keyEncrypt;
		$to_be_decrypted = pack ( 'H*', $to_be_decrypted );
		$key = $this->mysql_aes_key ( $key );
		return rtrim ( mcrypt_decrypt ( MCRYPT_RIJNDAEL_128, $key, $to_be_decrypted, MCRYPT_MODE_ECB, '' ), "\x00..\x1F" );
	}
}