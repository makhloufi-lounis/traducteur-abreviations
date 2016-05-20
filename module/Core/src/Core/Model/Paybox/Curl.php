<?php

namespace Core\Model\Paybox;

class PayboxCurl {
	public $message;
	public $url_envoi;
	function __construct($message, $url_envoi) {
		$this->url_envoi = $url_envoi;
		$this->message = $message;
	}
	function envoi_question_serveur_distant() {
		
		// echo"<br><br><b>question:</b>$this->message";
		// echo"<br><br><b>url:</b>$this->url_envoi";
		$ch = curl_init ();
		
		curl_setopt ( $ch, CURLOPT_URL, $this->url_envoi );
		curl_setopt ( $ch, CURLOPT_HEADER, FALSE );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt ( $ch, CURLOPT_POST, TRUE );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $this->message );
		
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 ); // !!!!!! � mettre absoluement sinon, la liste de certificats existants ne sera pas prise en compte
		                                             
		// curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		                                             // curl_setopt($ch, CURLOPT_CAINFO, "C:/Program Files/Apach Group/Apache2/conf/ssl/web.fusacq.com.cert" );
		                                             
		// curl_setopt( $ch,CURLOPT_FOLLOWLOCATION, TRUE );
		                                             
		// en commentaires pour le moment
		                                             // if( curl_error($ch) ) return "Erreur CURL";
		                                             
		// c'est parti ...
		$returned = curl_exec ( $ch );
		
		/*
		 * echo"<br>message:$this->message<br><br>"; if (!$returned) { echo"<br><b>erreur:</b>".curl_error($ch); } else { echo $returned; }
		 */
		
		curl_close ( $ch );
		
		return $returned;
	}
	function affecte_option() {
	}
}
?>