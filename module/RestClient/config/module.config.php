<?php
return array (
		'service_manager' => array (
				'factories' => array (
						'RestClient\HttpRestJson\Client' => function ($serviceManager) {
							$httpClient = $serviceManager->get ( 'HttpClient' );
							$httpRestJsonClient = new RestClient\HttpRestJson\Client($httpClient);
							return $httpRestJsonClient;
						},
						'HttpClient' => function ($serviceManager) {
							$httpClient = new Zend\Http\Client ();
							$httpClient->setAdapter ( 'Zend\Http\Client\Adapter\Curl' );
							return $httpClient;
						} 
				),
				'aliases' => array (
						'json_rest_client' => 'RestClient\HttpRestJson\Client' 
				) 
		) 
);
