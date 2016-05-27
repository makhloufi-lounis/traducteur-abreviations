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
								"aclmanager" => "all" 
						) 
				),
				User::ROLE_SOUSTRAITANT => array (
						'allow' => "all",
						'deny' => array (
								"aclmanager" => "all" 
						) 
				),
				User::ROLE_GUEST => array (
						'allow' => array (
								"ZfcUser" => "all",
								"adminindex" => array (
										"index" 
								) 
						),
						'deny' => "all" 
				) 
		) 
);
?>