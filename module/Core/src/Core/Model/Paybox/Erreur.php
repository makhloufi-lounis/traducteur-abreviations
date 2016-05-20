<?php

namespace Core\Model\Paybox;

class PayboxError {
	function enregistre_notification($numero_transaction, $numero_appel, $numero_question) {
		$db = Zend_Db_Table::getDefaultAdapter ();
		$requete_notif = "insert into fusacq_dbo.notifications_echec_transaction_paybox
									(numero_transaction,numero_appel,numero_question)
									values
									(?,?,?)	";
		$data = array (
				$numero_transaction,
				$numero_appel,
				$numero_question 
		);
		
		try {
			$stmt = $db->query ( $requete_notif, $data );
			return true;
		} catch ( exception $e ) {
			return false;
		}
	}
	function test_si_code_erreur_depend_du_client($code_erreur) {
		$db = Zend_Db_Table::getDefaultAdapter ();
		
		$requete = " select count(id_code) from fusacq_dbo.codes_reponse_paybox where id_code= ? and type_erreur='client'";
		// echo"$requete";
		$data = array (
				$code_erreur 
		);
		$stmt = $db->query ( $requete, $data );
		if ($recapitulatif = $stmt->fetch ()) {
			$res = true;
		} else {
			$res = false;
		}
		
		return $res;
	}
}
?>