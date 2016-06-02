<?php
use Core\Entity\User;
return array (
		'aclList' => array (
				User::ROLE_SUPERUSER => array (
						'allow' => "all",
						'deny' => array () 
				),
				User::ROLE_ADMIN => array (
						'allow' => "all",
						'deny' => array (
								"usermanagement" => "all" 
						) 
				),
				User::ROLE_SOUSTRAITANT => array (
						'allow' => "all",
						'deny' => array (
								"usermanagement" => "all" 
						) 
				),
				User::ROLE_GUEST => array (
						'allow' => array (
								"ZfcUser" => "all",
								"index" => array (
										"index" 
								) 
						),
						'deny' => "all" 
				) 
		) 
);
?>