<?php

namespace Core\Service;

class RandomPasswordGenerator {
	const TYPE_RANDOM_PASSWORD_NUMBER = 1;
	const TYPE_RANDOM_PASSWORD_ALPHA = 2;
	const TYPE_RANDOM_PASSWORD_ALPHA_NUMBER = 3;
	public function generate($typeRandomPassword, $digit) {
		switch ($typeRandomPassword) {
			case self::TYPE_RANDOM_PASSWORD_NUMBER :
				$chars = "0123456789";
				return substr ( str_shuffle ( $chars ), 0, $digit );
				break;
			case self::TYPE_RANDOM_PASSWORD_ALPHA :
				$chars = "azertyuiopqsdfghjklmwxcvbn";
				return substr ( str_shuffle ( $chars ), 0, $digit );
				break;
			case self::TYPE_RANDOM_PASSWORD_ALPHA_NUMBER :
				$chars = "azertyuiopqsdfghjklmwxcvbn0123456789";
				return substr ( str_shuffle ( $chars ), 0, $digit );
				break;
			default :
				$chars = "0123456789";
				return substr ( str_shuffle ( $chars ), 0, $digit );
				break;
		}
	}
}
