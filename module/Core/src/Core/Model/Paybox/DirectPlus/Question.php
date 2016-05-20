<?php

namespace Core\Model\Paybox\DirectPlus;

class PayboxDirectPlusQuestion {
	// CHAMPS OBLIGATOIRES
	public $VERSION = "00104"; // valeur numerique de la version PPS, valeur par defaut=00103 taille 5 chiffres
	public $DATEQ; // date et heure d'envoi de la trame au format jjmmaaaahhmmss
	               
	// en format de 5 chiffres
	               // 1 pour authorisation, 2 pour debit, 3 pour autorisation + debit, 4 pour credit, 5 pour annulation,
	               // 11 pour verification de l'existence d'une transaction, 12 pour transaction sans demande d'autorisation
	               // 13 modification du montant d'une transaction, 14 remboursement
	               // 51 pour autorisation seule sur un abonne, 52 pour debit sur un abonne, 53 autorisation + debit sur un abonne, 54 credit sur un abonne,
	               // 55 annulation d'une operation sur un abonne,
	               // 56 inscription nouvel abonne, 57 modif d'un abonne existant, 58 suppresion abonne
	               // 61 transaction sans demande d'autorisation (foracge)
	public $TYPE; // type de demande concernant la transaction cf la documentation pr connaitre les differentes valeurs,
	public $NUMQUESTION; // (peut etre remis � zero chaque jour, identifiant unique et sequentiel de la requete permettant d'eviterles confusions au niveau des reponses en cas de questions multiples et simultanees
	public $SITE; // numero d'adherent fourni par la banque du commer�ant
	public $RANG; // numero de rang du site fourni par la banque
	              
	// CHAMPS OBLIGATOIRES DANS CERTAINS CAS
	public $CLE; // cle active
	public $IDENTIFIANT; // champs vide � ne pas utiliser pourle moment
	public $MONTANT; // montant en centimes de la transaction
	public $DEVISE = "978"; // euro
	public $REFERENCE; // reference du commercant permettant d'identifier clairement la commande corresppondant � la transaction
	public $REFABONNE; // reference ducommer�ant permettannt d'identifier clairement l'abonnecorrespondant � la transaction
	public $PORTEUR; // numero de carte du porteur (partiel ... cf doc)
	public $DATEVAL; // date de fin de validite de la carte
	public $CVV; // cryptogramme visuel situ� au dos de la carte
	public $ACTIVITE = "024"; // indicateur de commerce electronique permettant de differencier la provenance de differents flux monetiques envoyes
	public $ARCHIVAGE; // r�ference transmise � votre banque lors de la elecollecte, elle devrait etre unique et peut permettre � votre banque de vous fournir des infos en cas de litige sur un paiement
	public $DIFFERE = "000"; // nb de jours avt la mise � dispo de la transaction
	public $NUMAPPEL; // cf doc
	public $NUMTRANS; // cf doc
	public $AUTORISATION; // cf doc
	public $PAYS = ""; // indcication sue le code pays doit etre retourne suivant la norme ISO lors de la reponse
	public $PRIV_CODETRAITEMENT;
	public $DATENAISS;
	public $ACQUEREUR;
	function __construct($type_abo, $identifiant, $montant, $date_mois, $type_question) {
		$paybox_params = Zend_Registry::get ( 'configPaybox' )->toArray ();
		
		$this->SITE = $paybox_params ["paybox"] ["numeroSitePaybox"]; // numero d'adherent fourni par la banque du commer�ant
		$this->RANG = $paybox_params ["paybox"] ["numeroRangPaybox"]; // numero de rang du site fourni par la banque
		$this->CLE = $paybox_params ["paybox"] ["clePaybox"]; // cle active
		
		$db = Zend_Db_Table::getDefaultAdapter ();
		
		// POUR SUPPRIME L'ABONNEMENT D'UN
		if ($type_question == "00058") {
			$refabonne = $identifiant;
			$reference_commande = "supprimer_" . $identifiant;
		} else {
			
			if ($type_abo == "u") {
				
				// RECUPERATION DES INFOS DELA CARTE:
				
				$date_actuelle = date ( 'Ymd' );
				$date_actuelle_format_fin_carte = substr ( $date_actuelle, 2, 4 );
				
				$requete = " select reference_commande,donnees_direct_plus,date_fin_carte,bin6,numero_abonnement
						   from fusacq_dbo.abonnements_prestataire_gestion where id_utilisateur=$identifiant and code_erreur='00000' and date_fin_carte>='$date_actuelle_format_fin_carte'
						   order by id_abonnement DESC limit 1 ";
				// echo"$requete";
				$data = array ();
				$stmt = $db->query ( $requete, $data );
				
				if ($recapitulatif = $stmt->fetch ()) { // ON TESTE SI UNE CB VALIDE EXISTE
					$refabonne = $recapitulatif ["reference_commande"];
					$id_societe_prestataire = $recapitulatif ["id_societe_prestataire"];
					$donnees_direct_plus = $recapitulatif ["donnees_direct_plus"];
				} else { // SI AUCUNE CB VALIDE DETECTE, ON SE RAPPATRIE SUR UNE CB QUI A ETE VALIDE UN JOUR
					$requete_secours = " select reference_commande,donnees_direct_plus,date_fin_carte,bin6,numero_abonnement
					from fusacq_dbo.abonnements_prestataire_gestion where id_utilisateur=$identifiant and 	code_erreur='00000' order by id_abonnement DESC limit 1 ";
					$stmt_secours = $db->query ( $requete_secours, $data );
					
					if ($recapitulatif = $stmt_secours->fetch ()) { // ON TESTE SI UNE CB VALIDE EXISTE
						$refabonne = $recapitulatif ["reference_commande"];
						$id_societe_prestataire = $recapitulatif ["id_societe_prestataire"];
						$donnees_direct_plus = $recapitulatif ["donnees_direct_plus"];
					} else {
						$erreur = "aucune cb d�tect�e pour la soci�t� de prestataire $identifiant = abonn� inexistant\r\n";
						// echo"<p>erreur = $erreur</p>\n";
					}
				}
				
				$tab_donnees = explode ( "++", $donnees_direct_plus ); // deux espaces!!!!! DEUUUUUUUUUUUUUUUUUUUUX!!!
				                                                 // echo"<p>donnees = $donnees_direct_plus,tab donnees : ";print_r($tab_donnees);echo"</p>\n";
				$partie_carte_cryptee = $tab_donnees [0];
				$dateval = $tab_donnees [1];
				
				// remise en ordre de la fin de validite de la carte
				$dateval = substr ( $dateval, 2, 4 ) . substr ( $dateval, 0, 2 );
				$cvv = $tab_donnees [2];
				// print_r($tab_donnees);
				$reference_commande = $type_abo . "_" . $identifiant . "_" . $date_mois;
			}
		}
		
		$madate = date ( 'Ymd' );
		$requete = " select numero_question from fusacq_dbo.abonnements_prestataire_admin where date_prelevement='$madate'  order by numero_question desc limit 1";
		// echo $requete;
		
		$data = array ();
		$stmt = $db->query ( $requete, $data );
		if ($recapitulatif = $stmt->fetch ()) { // ON TESTE SI UNE CB VALIDE EXISTE
			$numero_question = $recapitulatif ["numero_question"] + 1;
		} else
			$numero_question = 1;
		
		$numero_question = rand ( 1, 9999999999 );
		
		$this->TYPE = $type_question;
		
		$this->DATEQ = date ( 'dmYhis' ); // Pdc_Date::formatage_date_actuelle_complete_pour_paybox();
		
		$this->NUMQUESTION = $numero_question;
		
		$this->IDENTIFIANT = "";
		
		$this->MONTANT = $montant * 100;
		
		$this->REFERENCE = $reference_commande;
		
		$this->REFABONNE = $refabonne;
		
		// $this->PORTEUR="1111222233334444???";
		$this->PORTEUR = $partie_carte_cryptee;
		$this->CVV = "$cvv";
		$this->DATEVAL = "$dateval";
		
		$this->ARCHIVAGE = "";
		$this->NUMAPPEL = "";
		$this->NUMTRANS = "";
		$this->AUTORISATION = "";
		$this->PAYS = "";
		
		$this->PRIV_CODETRAITEMENT = "";
		$this->DATENAISS = "";
		$this->ACQUEREUR = "";
	}
	function donne_trame_question() {
		$message = "";
		
		$message .= "VERSION=$this->VERSION";
		$message .= "&DATEQ=$this->DATEQ";
		$message .= "&TYPE=$this->TYPE";
		$message .= "&NUMQUESTION=$this->NUMQUESTION";
		$message .= "&SITE=$this->SITE";
		$message .= "&RANG=$this->RANG";
		
		$message .= "&CLE=$this->CLE";
		
		$message .= "&IDENTIFIANT=$this->IDENTIFIANT";
		
		$message .= "&MONTANT=$this->MONTANT";
		
		$message .= "&DEVISE=$this->DEVISE";
		
		$message .= "&REFERENCE=" . urlencode ( $this->REFERENCE );
		$message .= "&REFABONNE=" . urlencode ( $this->REFABONNE );
		
		$message .= "&PORTEUR=$this->PORTEUR";
		$message .= "&DATEVAL=$this->DATEVAL";
		$message .= "&CVV=$this->CVV";
		
		$message .= "&ACTIVITE=$this->ACTIVITE";
		$message .= "&ARCHIVAGE=" . urlencode ( $this->ARCHIVAGE );
		$message .= "&DIFFERE=$this->DIFFERE";
		$message .= "&NUMAPPEL=$this->NUMAPPEL";
		$message .= "&NUMTRANS=$this->NUMTRANS";
		$message .= "&AUTORISATION=$this->AUTORISATION";
		
		return $message;
	}
}
?>