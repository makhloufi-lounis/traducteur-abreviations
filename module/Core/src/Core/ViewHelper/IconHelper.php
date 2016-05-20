<?php

namespace Core\ViewHelper;

use Zend\View\Helper\AbstractHelper;
use Core\Entity\Fiche;
use Core\Model\Utilisateurs;
use Core\Entity\Contact;
use Core\Entity\PrecisionContact;
use Core\Entity\EchangeContact;
use Core\Entity\RelanceContact;
use Core\Entity\Actualite;

class IconHelper extends AbstractHelper {
	const ETAT_FICHE = 10;
	const STATUS_FICHE = 11;
	const ETAT_CONTACT = 20;
	const ETAT_CONTACT_CANDIDAT = 200;
	const ETAT_CONTACT_CANDIDAT_PUBLIC = 201;
	const ETAT_CONTACT_CANDIDAT_PUBLIC_SMALL = 202;
	const STATUS_CONTACT = 21;
	const ETAT_PRECISION = 30;
	const ETAT_ECHANGE = 40;
	const ETAT_RELANCE = 50;
	const MODE_UTILISATEUR = 60;
	const TYPE_UTILISATEUR = 70;
	const PLATEFORME_INSCRIPTION = 80;
	const ETAT_ACTU = 90;
	protected $type;
	protected $catogery;
	public function __invoke($catogery, $type) {
		$this->type = $type;
		$this->catogery = $catogery;
		return $this->generateIcon ();
	}
	private function generateIcon() {
		$icons = array (
				self::ETAT_FICHE => array (
						Fiche::ETAT_FICHE_CREATION => '<i class="fa fa-lg fa-pencil" style="color: grey;"></i>',
						Fiche::ETAT_FICHE_PUBLIQUE => '<i class="fa fa-lg fa-check" style="color: green;"></i>',
						Fiche::ETAT_FICHE_DEMANDE_PUBLICATION => '<i class="fa fa-lg fa-vine" style="color: orange;"></i>',
						Fiche::ETAT_FICHE_EN_ATTENTE => '<i class="fa fa-lg fa-pause" style="color: purple;"></i>',
						Fiche::ETAT_FICHE_SUPPRIMEE => '<i class="fa fa-trash-o fa-lg" style="color: grey;"></i>' 
				),
				self::ETAT_CONTACT => array (
						Contact::ETAT_CONTACT_TRANSMIS => '<i class="fa fa-paper-plane" style="color: green;"></i>',
						Contact::ETAT_CONTACT_A_VALIDER => '<i class="fa fa-lg fa-vine" style="color: orange;"></i>',
						Contact::ETAT_CONTACT_ACCEPTE => '<i class="fa fa-lg fa-check" style="color: green;"></i>',
						Contact::ETAT_CONTACT_REFUSE => '<i class="fa fa-times" style="color: red;"></i>',
						Contact::ETAT_CONTACT_ARCHIVE => '<i class="fa fa-envelope" style="color: brown;"></i>',
						Contact::IS_CONTACT_DEJA_CONNU => '<i class="fa fa-coffee" style="color: black;"></i>',
						Contact::ETAT_CONTACT_SUPPRIME => '<i class="fa fa-trash-o fa-lg" style="color: grey;"></i>' 
				),
				self::ETAT_CONTACT_CANDIDAT => array (
						Contact::ETAT_CONTACT_TRANSMIS => '<i class="fa fa-question fa-2x"></i>',
						Contact::ETAT_CONTACT_A_VALIDER => '<i class="fa fa-question fa-2x"></i>',
						Contact::ETAT_CONTACT_HAS_ECHANGE => '<i class="fa fa-lg fa-pencil fa-2x" style="color: grey;"></i>',
						Contact::ETAT_CONTACT_ACCEPTE => '<i class="fa fa-lg fa-check fa-2x" style="color: green;"></i>',
						Contact::ETAT_CONTACT_REFUSE => '<i class="fa fa-times fa-2x" style="color: red;"></i>',
						Contact::ETAT_CONTACT_ARCHIVE => '<i class="fa fa-lg fa-check fa-2x" style="color: green;"></i>',
						Contact::IS_CONTACT_DEJA_CONNU => '<i class="fa fa-lg fa-check fa-2x" style="color: green;"></i>' 
				),
				self::ETAT_CONTACT_CANDIDAT_PUBLIC => array (
						Contact::ETAT_CONTACT_TRANSMIS => '<i class="fa fa-lg fa-circle" style="color: #F60;"></i>',
						Contact::ETAT_CONTACT_A_VALIDER => '<i class="fa fa-lg fa-circle" style="color: #F60"></i>',
						Contact::ETAT_CONTACT_HAS_ECHANGE => '<i class="fa fa-lg fa-pencil" style="color: grey;"></i>',
						Contact::ETAT_CONTACT_ACCEPTE => '<i class="fa fa-lg fa-circle" style="color: #6d8922;"></i>',
						Contact::ETAT_CONTACT_REFUSE => '<i class="fa fa-lg fa-circle" style="color: #C00;"></i>',
						Contact::ETAT_CONTACT_ARCHIVE => '<i class="fa fa-lg fa-circle" style="color: #6d8922;"></i>',
						Contact::IS_CONTACT_DEJA_CONNU => '<i class="fa fa-lg fa-circle" style="color: #6d8922;"></i>' 
				),
				self::ETAT_CONTACT_CANDIDAT_PUBLIC_SMALL => array (
						Contact::ETAT_CONTACT_TRANSMIS => '<i class="fa fa-circle" style="color: #F60;"></i>',
						Contact::ETAT_CONTACT_A_VALIDER => '<i class="fa fa-circle" style="color: #F60"></i>',
						Contact::ETAT_CONTACT_HAS_ECHANGE => '<i class="fa fa-pencil" style="color: grey;"></i>',
						Contact::ETAT_CONTACT_ACCEPTE => '<i class="fa fa-circle" style="color: #6d8922;"></i>',
						Contact::ETAT_CONTACT_REFUSE => '<i class="fa fa-circle" style="color: #C00;"></i>',
						Contact::ETAT_CONTACT_ARCHIVE => '<i class="fa fa-circle" style="color: #6d8922;"></i>',
						Contact::IS_CONTACT_DEJA_CONNU => '<i class="fa fa-circle" style="color: #6d8922;"></i>' 
				),
				self::ETAT_PRECISION => array (
						PrecisionContact::ETAT_PRECISION_A_VALIDER => '<i class="fa fa-lg fa-vine" style="color: orange;"></i>',
						PrecisionContact::ETAT_PRECISION_TRANSMIS => '<i class="fa fa-paper-plane" style="color: green;"></i>',
						PrecisionContact::ETAT_PRECISION_SUPPRIME => '<i class="fa fa-trash-o fa-lg" style="color: grey;"></i>' 
				),
				self::ETAT_ECHANGE => array (
						EchangeContact::ETAT_ECHANGE_A_VALIDER => '<i class="fa fa-lg fa-vine" style="color: orange;"></i>',
						EchangeContact::ETAT_ECHANGE_TRANSMIS => '<i class="fa fa-paper-plane" style="color: green;"></i>',
						EchangeContact::ETAT_ECHANGE_SUPPRIME => '<i class="fa fa-trash-o fa-lg" style="color: grey;"></i>' 
				),
				self::ETAT_RELANCE => array (
						RelanceContact::ETAT_RELANCE_A_VALIDER => '<i class="fa fa-lg fa-vine" style="color: orange;"></i>',
						RelanceContact::ETAT_RELANCE_TRANSMIS => '<i class="fa fa-paper-plane" style="color: green;"></i>',
						RelanceContact::ETAT_RELANCE_SUPPRIME => '<i class="fa fa-trash-o fa-lg" style="color: grey;"></i>' 
				),
				self::ETAT_ACTU => array (
						Actualite::ETAT_ACTU_A_VALIDER => '<i class="fa fa-lg fa-vine" style="color: orange;"></i>',
						Actualite::ETAT_ACTU_PUBLIQUE => '<i class="fa fa-lg fa-check" style="color: green;"></i>',
						Actualite::ETAT_ACTU_EN_ATTENTE => '<i class="fa fa-lg fa-pause" style="color: purple;"></i>',
						Actualite::ETAT_ACTU_SUPPRIME => '<i class="fa fa-lg fa-trash-o" style="color: grey;"></i>',
				),
				self::STATUS_CONTACT => array (
						Contact::STATUS_CONTACT_OK_POUR_TRANSMISSION => '<i
					class="fa fa-lg fa-check-circle-o" style="color: orange;"></i>',
						Contact::STATUS_CONTACT_COMPLEX_A_TRAITER => '<i
					class="fa fa-lg fa-question-circle" style="color: orange;"></i>',
						Contact::STATUS_CONTACT_ATTENTE_RETOUR => '<i
					class="fa fa-lg fa-circle-o-notch" style="color: orange;"></i>' 
				),
				self::STATUS_FICHE => array (
						Fiche::STATUS_FICHE_A_REVALIDER => '<i
					class="fa fa-lg fa-thumb-tack" style="color: grey;"></i>' 
				),
				self::MODE_UTILISATEUR => array (
						Utilisateurs::MODE_UTILISATEUR_DEMANDE_INSCRIPTION => '<i class="fa fa-lg fa-vine" style="color: orange;"></i>',
						Utilisateurs::MODE_UTILISATEUR_INSCRIT => '<i class="fa fa-lg fa-check" style="color: green;"></i>',
						Utilisateurs::MODE_UTILISATEUR_SUPPRIME => '<i class="fa fa-trash-o fa-lg" style="color: grey;"></i>',
						Utilisateurs::MODE_UTILISATEUR_RADIE => '<i class="fa fa-trash-o fa-lg" style="color: grey;"></i>' 
				),
				self::TYPE_UTILISATEUR => array (
						Utilisateurs::TYPE_UTILISATEUR_FRANCHISEUR => '<i class="fa fa-facebook fa-lg"></i>',
						Utilisateurs::TYPE_UTILISATEUR_CANDIDAT => '<i class="fa fa-hand-o-up fa-lg"></i>',
						Utilisateurs::TYPE_UTILISATEUR_AUTRE => '<i class="fa fa-paw fa-lg"></i>' 
				),
				self::PLATEFORME_INSCRIPTION => array (
						Utilisateurs::PLATEFORME_INSCRIPTION_FRANCHISES => '<b>F</b><small>ranchises</small>',
						Utilisateurs::PLATEFORME_INSCRIPTION_COMMERCES => '<b>C</b><small>ommerces</small>',
						Utilisateurs::PLATEFORME_INSCRIPTION_FUSACQ => '<b>F</b><small>usacq</small>' 
				) 
		);
		
		return $icons [$this->catogery] [$this->type];
	}
}
