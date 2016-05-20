<?php

namespace Core\Model\Paybox\DirectPlus;
class PayboxDirectPlusReponse {
	public $NUMTRANS; // numero de la banque geree
	public $NUMAPPEL; // numero de la banque geree
	public $NUMQUESTION; //
	public $SITE; //
	public $RANG;
	public $IDENTIFIANT;
	public $AUTORISATION;
	public $CODEREPONSE;
	public $REFABONNE;
	public $PORTEUR;
	public $COMMENTAIRE;
	public $PAYS;
	
	// en cas d'utilisation pour un utilisateur :
	public $id_utilisateur;
	// en cas d'utilisation pour l'annuaire des prestataires
	public $id_societe_prestataire;
	public $type_question;
	public $date_mois;
	function __construct($trame_reponse, $type_question, $reference_commande) {
		$infos = array ();
		$tab_donnees = explode ( "&", $trame_reponse );
		for($i = 0; $i < count ( $tab_donnees ); $i ++) {
			$temp = explode ( "=", $tab_donnees [$i] );
			$infos [$temp [0]] = $temp [1];
		}
		
		// echo"<br>";print_r($infos);echo"<br>";
		
		$this->NUMTRANS = $infos ["NUMTRANS"];
		$this->NUMAPPEL = $infos ["NUMAPPEL"];
		$this->NUMQUESTION = $infos ["NUMQUESTION"];
		$this->SITE = $infos ["SITE"];
		$this->RANG = $infos ["RANG"];
		$this->IDENTIFIANT = $infos ["IDENTIFIANT"];
		$this->AUTORISATION = $infos ["AUTORISATION"];
		$this->CODEREPONSE = $infos ["CODEREPONSE"];
		$this->REFABONNE = $infos ["REFABONNE"];
		$this->PORTEUR = $infos ["PORTEUR"];
		$this->COMMENTAIRE = $infos ["COMMENTAIRE"];
		$this->PAYS = $infos ["PAYS"];
		
		$this->date_prelevement = date ( 'Ymd' );
		
		$ref = $infos ["REFABONNE"];
		
		if (preg_match ( "/^u_([0-9]+)_([0-9]+)$/", $reference_commande, $matches ) == 1) {
			$this->id_utilisateur = ( int ) $matches [1];
			$this->date_mois = $matches [2];
		} elseif (preg_match ( "/^u_([0-9]+)_([0-9]+)$/", $ref, $matches ) == 1) {
			$this->id_utilisateur = ( int ) $matches [1];
			$this->date_mois = null;
		} else
			$this->id_utilisateur = 0;
		
		$this->type_question = $type_question;
	}
	public function enregistre_prelevement_utilisateur() {
		$abonnements = new Model_DbTable_AbonnementsPrestataireAdmin ();
		$abonnement = $abonnements->createRow ();
		
		$abonnement->numero_transaction = $this->NUMTRANS;
		$abonnement->numero_appel = $this->NUMAPPEL;
		$abonnement->numero_question = $this->NUMQUESTION;
		$abonnement->numero_autorisation = $this->AUTORISATION;
		$abonnement->code_reponse = $this->CODEREPONSE;
		$abonnement->reference_abonne = $this->REFABONNE;
		$abonnement->id_utilisateur = $this->id_utilisateur;
		$abonnement->date_prelevement = $this->date_prelevement;
		$abonnement->type_question = $this->type_question;
		$abonnement->date_mois = $this->date_mois;
		
		try {
			$abonnement->save ();
			return true;
		} catch ( Exception $e ) {
			return false;
		}
	}
}
?>