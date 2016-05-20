<?php

namespace Core\Model;

class Utilisateurs {
	const MODE_UTILISATEUR_DEMANDE_INSCRIPTION = 'demande_inscription';
	const MODE_UTILISATEUR_SUPPRIME = 'supprime';
	const MODE_UTILISATEUR_RADIE = 'radie';
	const MODE_UTILISATEUR_INSCRIT = 'inscrit';
	const ETAT_RELANCE_ACTIF = 'Actif';
	const ETAT_RELANCE_ATTENTE = 'Attente';
	const ETAT_RELANCE_AVORTE = 'Avorte';
	const PLATEFORME_INSCRIPTION_FRANCHISES = 'placedesfranchises';
	const PLATEFORME_INSCRIPTION_COMMERCES = 'placedescommerces';
	const PLATEFORME_INSCRIPTION_FUSACQ = 'fusacq';
	const TYPE_UTILISATEUR_FRANCHISEUR = 'franchiseur';
	const TYPE_UTILISATEUR_CANDIDAT = 'candidat';
	const TYPE_UTILISATEUR_AUTRE = 'autre';
	const CIVILITE_MONSIEUR = 'masculin';
	const CIVILITE_MADAME = 'feminin';
	const MODE_REGLEMENT_FRANCHISES_CHEQUE = 'cheque';
	const MODE_REGLEMENT_FRANCHISES_NULL = '';
	public $fields = array (
			'id_utilisateur',
			'email_utilisateur',
			'login_utilisateur',
			'password_encrypted_utilisateur',
			'type_utilisateur_franchise',
			'annee_naissance',
			'civilite',
			'nom_utilisateur',
			'prenom_utilisateur',
			'telephone_utilisateur',
			'societe_utilisateur',
			'adresse_utilisateur',
			'ville_utilisateur',
			'code_postal_utilisateur',
			'mode_utilisateur',
			'date_demande_inscription',
			'date_inscription_reelle',
			'plateforme_inscription',
			'suivi',
			'date_relance',
			'etat_relance',
			'mode_reglement_franchise',
			'pays_utilisateur' 
	);
	public function exchangeArray($data) {
		foreach ( $this->fields as $field ) {
			$this->$field = (isset ( $data [$field] )) ? $data [$field] : null;
		}
	}
	public function toArray() {
		$data = array ();
		foreach ( $this->fields as $field ) {
			$data [$field] = (isset ( $this->$field )) ? $this->$field : null;
		}
		return $data;
	}
	public function isAuthorizedCommand() {
		if ($this->mode_reglement_franchise) {
			return true;
		}
		return false;
	}
}