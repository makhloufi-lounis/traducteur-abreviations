<?php

namespace Core\Service\Encryptage;


interface EncryptageStrategyInterface {
	public function encryptage($to_be_encrypted);
	public function decryptage($to_be_decrypted);
}