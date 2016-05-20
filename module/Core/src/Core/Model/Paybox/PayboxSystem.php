<?php

namespace Core\Model\Paybox;

class PayboxSystem extends AbstractPaybox {
	public $emplacement_fichier_paybox;
	// **************************************************************************//
	// * VARIABLES OBLIGATOIRES *//
	// **************************************************************************//
	public $PBX_MODE = 13; // mode de recuperation des informations. de 1 a 4 chiffres parmi les valeurs 1,2,3 et 4
	public $PBX_SITE; // numero de site donn� par la banque (cf page 65 de la doc )
	public $PBX_RANG; // numero de rang "machine" donn� par la banque (cf page 65 de la doc )
	public $PBX_TOTAL; // montant total de l'achat en CENTIMES , doit �tre un NOMBRE ENTIER
	public $PBX_DEVISE = '978'; // code de la devise, 978 pour l'euro
	public $PBX_CMD; // la reference commande fournie par fusacq
	public $PBX_PORTEUR; // adresse email de l'acheteur, doit imperativement comporter @ et .
	public $PBX_RETOUR; // variable renvoy�e par paybox (montant, reference commande, numero de transaction, numero d'abonnement et numero d'autorisation)
	public $PBX_IDENTIFIANT; // identifiant paybox fournit par Paybox services au moment de l'inscription de fusacq sur paybox
	                         
	// **************************************************************************//
	                         // * VARIABLES FACULTATIVES *//
	                         // **************************************************************************//
	public $PBX_EFFECTUE; // page de retour pour un paiement accepte
	public $PBX_REFUSE; // page de retour pour un paiement refuse
	public $PBX_ANNULE; // page de retour pour un paiement annule
	public $PBX_TXT; // texte pouvant �tre affiche sur la page intermediaire � la place du texte par defaut
	public $PBX_BOUTPI; // intitule du bouton de la page intermediaire ('nul' pr la suppression de ce bouton)
	public $PBX_BKGD; // fond d'ecran de la page intermediaire (nom de couleur, code couleur ou image)
	public $PBX_OUTPUT; // mode de gestion de la page intermediaire, voir la doc paybox
	public $PBX_WAIT; // tps d'affichage de la page intermediaire
	public $PBX_AUTOSEULE; // il faut imperativement parametrer cette variable � 'O' pour pouvoir effectuer une autorisation seule
	public $PBX_RUF1; // methode utilis�e pour l'appel de <<l'url http directe>> par defaut, c'est GET
	                  
	// **************************************************************************//
	                  // * CONSTRUCTEUR *//
	                  // **************************************************************************//
	public function __construct($reference, $montant, $email_acheteur, $params) {
		/*
		 * 1) variables paybox définies grace aux parametres du constructeur de la classe: type: s'il s'agit de prestataire, ou d'autres choses, identifiant: identifiant de la table concern�e correspondant au type $type + $identifiant construisent le PBX_CMD $montant: montant en euros, on multiplie par 100 pr avoir le format de PBX_TOTAl (le montant doit comprendre au minimum 3 caracteres, autorisation doit valoir 'O' ou 'N'
		 */
		// $config = $this->getServiceLocator ()->get ( "Config" );
		$paybox_params = $params;
		// echo var_dump ( $config );
		$this->PBX_SITE = $paybox_params ["paybox"] ["numeroSitePaybox"];
		$this->PBX_RANG = $paybox_params ["paybox"] ["numeroRangPaybox"];
		$this->PBX_IDENTIFIANT = $paybox_params ["paybox"] ["identifiantPaybox"];
		
		// $partenaire = new Pdc_Partenaire ( CODE_PARTENAIRE );
		// $this->PBX_EFFECTUE = str_replace ( DNS_PLACEDESCOMMERCES, $partenaire->dns, $paybox_params ["paybox"] ["urlPayboxSystemAccepte"] );
		// $this->PBX_REFUSE = str_replace ( DNS_PLACEDESCOMMERCES, $partenaire->dns, $paybox_params ["paybox"] ["urlPayboxSystemRefuse"] );
		// $this->PBX_ANNULE = str_replace ( DNS_PLACEDESCOMMERCES, $partenaire->dns, $paybox_params ["paybox"] ["urlPayboxSystemAnnule"] );
		
		$this->PBX_EFFECTUE = $paybox_params ["paybox"] ["urlPayboxSystemAccepte"];
		$this->PBX_REFUSE = $paybox_params ["paybox"] ["urlPayboxSystemRefuse"];
		$this->PBX_ANNULE = $paybox_params ["paybox"] ["urlPayboxSystemAnnule"];
		
		$this->PBX_CMD = $reference;
		
		$pos_premier_underscore = strpos ( $reference, "_" );
		$type = substr ( $reference, 0, $pos_premier_underscore );
		
		$this->PBX_TOTAL = $montant * 100;
		if ($montant == 0)
			$this->PBX_TOTAL = "000";
		
		if ($type == "u")
			$this->PBX_AUTOSEULE = "O";
		else
			$this->PBX_AUTOSEULE = "N";
		
		$this->PBX_PORTEUR = $email_acheteur;
		
		/*
		 * 2) composition de la variable PBX_RETOUR
		 */
		
		$this->PBX_RETOUR = "";
		$this->PBX_RETOUR .= "montant:M;"; // montant de la commande
		$this->PBX_RETOUR .= "ref:R;"; // reference fusacq de la commande precis� ds PBX_CMD
		$this->PBX_RETOUR .= "id_trans:T;"; // numero d'appel sequentiel paybox service
		$this->PBX_RETOUR .= "num_auth:A;"; // numero d'authorisation remis par le centre
		$this->PBX_RETOUR .= "num_abo:B;"; // numero d'abonnement remis par paybox
		$this->PBX_RETOUR .= "type_paie:P;"; // type de paiement retenu
		$this->PBX_RETOUR .= "type_carte:C;"; // type de carte utilis�
		$this->PBX_RETOUR .= "num_trans:S;"; // numero de transaction
		$this->PBX_RETOUR .= "erreur:E;"; // code erreur
		$this->PBX_RETOUR .= "fin:D;"; // fin de validite de la carte
		$this->PBX_RETOUR .= "cb:N;"; // 6 premiers chiffres bin6 du num de carte de l'acheteur
		$this->PBX_RETOUR .= "garantie:G;"; // garantie du paiement O ou N (pgm 3D secure)
		$this->PBX_RETOUR .= "dig:H;"; // emprunte de la carte (pgm 3D secure
		$this->PBX_RETOUR .= "enrol:O;"; // enrolement du porteur, 3D secure (Y pour enroleou N pour non enrol� ou U pour inconnu )
		
		if ($type == "u")
			$this->PBX_RETOUR .= "gestion:U;"; // IMPORTANT, gestion des abonnements direct plus, renvoie les infos sur la carte en cod�
			
		/*
		 * 3 parametrage de la page intermediaire
		 */
		
		$texte = 'Vous allez être redirigê vers notre Partenaire Paybox afin de rentrer vos coordonnées bancaires';
		$this->PBX_TXT = $texte;
		$this->PBX_BOUPI = "nul";
		$this->PBX_BKGD = "\"#E7E7E7\"";
		
		$this->emplacement_fichier_paybox = $paybox_params ["paybox"] ["emplacementFichierpaybox"];
	}
	public function donne_tableau_parametres_envoi_demande_paiement() {
		$parametres = array ();
		
		$parametres ["PBX_MODE"] = $this->PBX_MODE;
		
		$parametres ["PBX_SITE"] = $this->PBX_SITE;
		$parametres ["PBX_RANG"] = $this->PBX_RANG;
		
		$parametres ["PBX_TOTAL"] = $this->PBX_TOTAL;
		$parametres ["PBX_DEVISE"] = $this->PBX_DEVISE;
		
		// si on utilise deux fois le meme parametre pour 2 paiement different
		// pay box ne laccepte pas, meme pas en test
		$parametres ["PBX_CMD"] = $this->PBX_CMD;
		$parametres ["PBX_PORTEUR"] = $this->PBX_PORTEUR;
		
		$parametres ["PBX_RETOUR"] = $this->PBX_RETOUR;
		
		$parametres ["PBX_IDENTIFIANT"] = $this->PBX_IDENTIFIANT;
		
		$parametres ["PBX_EFFECTUE"] = $this->PBX_EFFECTUE;
		$parametres ["PBX_REFUSE"] = $this->PBX_REFUSE;
		$parametres ["PBX_ANNULE"] = $this->PBX_ANNULE;
		$parametres ["PBX_TXT"] = $this->PBX_TXT;
		$parametres ["PBX_BOUTPI"] = $this->PBX_BOUTPI;
		$parametres ["PBX_BKGD"] = $this->PBX_BKGD;
		$parametres ["PBX_OUTPUT"] = $this->PBX_OUTPUT;
		$parametres ["PBX_OUTPUT"] = $this->PBX_OUTPUT;
		
		$parametres ["PBX_AUTOSEULE"] = $this->PBX_AUTOSEULE;
		
		return $parametres;
	}
	public function donne_url_paybox() {
		$parametres = $this->donne_tableau_parametres_envoi_demande_paiement ();
		$chaine_a_coder = md5 ( $this->PBX_CMD );
		$emplacement_fichier = $this->emplacement_fichier_paybox . "pbx_system_" . $chaine_a_coder . ".txt";
		$fichier_paybox_system = fopen ( $emplacement_fichier, "w" );
		foreach ( $parametres as $cle => $valeur ) {
			$phrase = "$cle=$valeur\r\n";
			fwrite ( $fichier_paybox_system, $phrase );
		}
		fclose ( $fichier_paybox_system );
		$page_paybox = "http://" . $_SERVER ["HTTP_HOST"] . "/cgi-bin/modulev3_windows.exe?PBX_MODE=13&PBX_OPT=$emplacement_fichier";
		return $page_paybox;
	}
}

?>