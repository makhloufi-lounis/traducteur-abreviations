<?php

namespace Core\Model;

use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\IsNotNull;
use Zend\Db\Sql\Predicate\Like;
use Zend\Db\Sql\Predicate\PredicateSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;

class UtilisateursTable {
	protected $tableGateway;
	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}
	public function fetchAll() {
		$resultSet = $this->tableGateway->select ();
		return $resultSet;
	}
	public function getUtilisateurById($id_utilisateur) {
		$id_utilisateur = ( int ) $id_utilisateur;
		$rowset = $this->tableGateway->select ( array (
				'id_utilisateur' => $id_utilisateur 
		) );
		$row = $rowset->current ();
		if (! $row) {
			return null;
		}
		return $row;
	}
	public function getUtilisateurByEmail($email_utilisateur) {
		$rowset = $this->tableGateway->select ( array (
				'email_utilisateur' => $email_utilisateur 
		) );
		$row = $rowset->current ();
		if (! $row) {
			return null;
		}
		return $row;
	}
	public function saveUtilisateur(Utilisateurs $utilisateur) {
		$data = $utilisateur->toArray ();
		$id_utilisateur = ($data ['id_utilisateur'] != null) ? ( int ) $utilisateur->id_utilisateur : 0;
		if ($id_utilisateur == 0) {
			try {
				$this->tableGateway->insert ( $data );
				return $this->tableGateway->getLastInsertValue ();
			} catch ( Exception $e ) {
				return false;
			}
		} else {
			if ($this->getUtilisateurById ( $id_utilisateur )) {
				try {
					
					$this->tableGateway->update ( $data, array (
							'id_utilisateur' => $id_utilisateur 
					) );
					return $id_utilisateur;
				} catch ( Exception $e ) {
					return false;
				}
			} else {
				throw new \Exception ( 'Utilisateur id does not exist' );
			}
		}
	}
	public function deleteUtilisateur($id_utilisateur) {
		try {
			
			$this->tableGateway->delete ( array (
					'id_utilisateur' => ( int ) $id_utilisateur 
			) );
			return true;
		} catch ( Exception $e ) {
			return false;
		}
	}
	public function createReferencePayboxSystem($id_utilisateur) {
		$abo = array ();
		$sql = new Sql ( $this->tableGateway->getAdapter () );
		$select = $sql->select ()->columns ( array (
				'id_utilisateur' 
		) )->from ( $this->tableGateway->getTable () )->join ( 'abonnements_prestataire_gestion', 'utilisateurs.id_utilisateur=abonnements_prestataire_gestion.id_utilisateur', array (
				"date_fin_carte",
				"reference_commande",
				"bin6" 
		), Select::JOIN_LEFT )->where ( array (
				'utilisateurs.id_utilisateur' => $id_utilisateur 
		) )->order ( 'abonnements_prestataire_gestion.id_abonnement desc' )->limit ( 1 );
		$statement = $sql->prepareStatementForSqlObject ( $select );
		$abo = $statement->execute ()->current ();
		if ($abo ["reference_commande"] == null) {
			$nouvelle_ref = "u_" . $id_utilisateur . "_1";
		} else {
			$derniere_ref = preg_split ( '/^u_' . $id_utilisateur . '_/', $abo ["reference_commande"] );
			$derniere_ref = ( int ) $derniere_ref;
			$nouvelle_ref = $derniere_ref + 1;
			$nouvelle_ref = "u_" . $id_utilisateur . "_" . $nouvelle_ref;
		}
		return $nouvelle_ref;
	}
	public function donneInfosCarteBancaireActive($id_utilisateur) {
		$row_abo = array ();
		$sql = new Sql ( $this->tableGateway->getAdapter () );
		$select = $sql->select ()->columns ( array (
				'id_utilisateur' 
		) )->from ( $this->tableGateway->getTable () )->join ( 'abonnements_prestataire_gestion', 'utilisateurs.id_utilisateur=abonnements_prestataire_gestion.id_utilisateur', array (
				"date_fin_carte",
				"reference_commande",
				"bin6" 
		), Select::JOIN_LEFT )->where ( array (
				'utilisateurs.id_utilisateur' => $id_utilisateur,
				'abonnements_prestataire_gestion.code_erreur' => '00000' 
		) )->order ( "abonnements_prestataire_gestion.id_abonnement desc" )->limit ( 1 );
		$statement = $sql->prepareStatementForSqlObject ( $select );
		$row_abo = $statement->execute ()->current ();
		return $row_abo;
	}
	public function getListUtilisateurByString($motscles) {
		$rows_results = array ();
		
		$sql = new Sql ( $this->tableGateway->getAdapter () );
		$select = $sql->select ()->columns ( array (
				
				'id_utilisateur',
				'nom_utilisateur',
				'prenom_utilisateur',
				'email_utilisateur' 
		) )->from ( $this->tableGateway->getTable () )->where ( array (
				new PredicateSet ( array (
						new Like ( 'nom_utilisateur', '%' . $motscles . '%' ),
						new Like ( 'email_utilisateur', '%' . $motscles . '%' ),
						new Like ( 'prenom_utilisateur', '%' . $motscles . '%' ) 
				), PredicateSet::COMBINED_BY_OR ) 
		) )->order ( "id_utilisateur desc" );
		$statement = $sql->prepareStatementForSqlObject ( $select );
		$rows_results = $statement->execute ();
		return $rows_results;
	}
	public function getNBListUtilisateurFranchises() {
		$sql = new Sql ( $this->tableGateway->getAdapter () );
		$select = $sql->select ()->columns(array('nb'=>new \Zend\Db\Sql\Expression('COUNT(*)')))->from ( $this->tableGateway->getTable () )->where ( array (
				new IsNotNull ( 'type_utilisateur_franchise' )
		) );
		
		$statement = $sql->prepareStatementForSqlObject($select);
		$rows_results = $statement->execute()->current();
// 		echo  $rows_results['nb'];die();
		return $rows_results['nb'];
		
		
	}
	public function getListUtilisateurFranchises($limit = null, $offset = null) {
		$rows_results = array ();
		$sql = new Sql ( $this->tableGateway->getAdapter () );
		$select = $sql->select ()->columns ( array (
				'id_utilisateur',
				'nom_utilisateur',
				'prenom_utilisateur',
				'email_utilisateur',
				'date_demande_inscription',
				'date_inscription_reelle',
				'plateforme_inscription',
				'type_utilisateur_franchise',
				'mode_utilisateur' 
		) )->from ( $this->tableGateway->getTable () )->where ( array (
				new IsNotNull ( 'type_utilisateur_franchise' ) 
		) );
		$select->order ( "id_utilisateur desc" );
		if (! is_null ( $limit ) && ! is_null ( $offset )) {
			$select->limit ( $limit )->offset ( $offset );
		}
		$statement = $sql->prepareStatementForSqlObject ( $select );
		$rows_results = $statement->execute ();
		return $rows_results;
	}
	public function getNBListUtilisateurFranchisesByString($motscles) {
		$sql = new Sql ( $this->tableGateway->getAdapter () );
		$select = $sql->select ()->columns(array('nb'=>new \Zend\Db\Sql\Expression('COUNT(*)')))->from ( $this->tableGateway->getTable () )->where ( array (
				new PredicateSet ( array (
						new Like ( 'nom_utilisateur', '%' . $motscles . '%' ),
						new Like ( 'email_utilisateur', '%' . $motscles . '%' ),
						new Like ( 'prenom_utilisateur', '%' . $motscles . '%' ),
						new Like ( 'id_utilisateur', '%' . $motscles . '%' )
				), PredicateSet::COMBINED_BY_OR )
		) );
		$select->where ( array (
				new IsNotNull ( 'type_utilisateur_franchise' )
		) );
		
		$statement = $sql->prepareStatementForSqlObject($select);
		$rows_results = $statement->execute()->current();
		return $rows_results['nb'];
		
	}
	public function getListUtilisateurFranchisesByString($motscles, $limit = null, $offset = null) {
		$rows_results = array ();
		$sql = new Sql ( $this->tableGateway->getAdapter () );
		$select = $sql->select ()->columns ( array (
				'id_utilisateur',
				'nom_utilisateur',
				'prenom_utilisateur',
				'email_utilisateur',
				'date_demande_inscription',
				'date_inscription_reelle',
				'plateforme_inscription',
				'type_utilisateur_franchise',
				'mode_utilisateur' 
		) )->from ( $this->tableGateway->getTable () )->where ( array (
				new PredicateSet ( array (
						new Like ( 'nom_utilisateur', '%' . $motscles . '%' ),
						new Like ( 'email_utilisateur', '%' . $motscles . '%' ),
						new Like ( 'prenom_utilisateur', '%' . $motscles . '%' ),
						new Like ( 'id_utilisateur', '%' . $motscles . '%' ) 
				), PredicateSet::COMBINED_BY_OR ) 
		) );
		$select->where ( array (
				new IsNotNull ( 'type_utilisateur_franchise' ) 
		) )->order ( "id_utilisateur desc" );
		if (! is_null ( $limit ) && ! is_null ( $offset )) {
			$select->limit ( $limit )->offset ( $offset );
		}
		
		$statement = $sql->prepareStatementForSqlObject ( $select );
		$rows_results = $statement->execute ();
		return $rows_results;
	}
	public function getNBListUtilisateurFranchisesByMode($mode) {

		$sql = new Sql ( $this->tableGateway->getAdapter () );
		$select = $sql->select ()->columns(array('nb'=>new \Zend\Db\Sql\Expression('COUNT(*)')))->from ( $this->tableGateway->getTable () )->where ( array (
				new IsNotNull ( 'type_utilisateur_franchise' ) 
		) )->where ( array (
				'utilisateurs.mode_utilisateur' => $mode 
		) );
		
		$statement = $sql->prepareStatementForSqlObject($select);
		$rows_results = $statement->execute()->current();
		return $rows_results['nb'];
		
		
	}
	public function getListUtilisateurFranchisesByMode($mode, $limit = null, $offset = null) {
		$rows_results = array ();
		$sql = new Sql ( $this->tableGateway->getAdapter () );
		$select = $sql->select ()->columns ( array (
				'id_utilisateur',
				'nom_utilisateur',
				'prenom_utilisateur',
				'email_utilisateur',
				'date_demande_inscription',
				'date_inscription_reelle',
				'plateforme_inscription',
				'type_utilisateur_franchise',
				'mode_utilisateur' 
		) )->from ( $this->tableGateway->getTable () )->where ( array (
				new IsNotNull ( 'type_utilisateur_franchise' ) 
		) );
		$select->where ( array (
				'utilisateurs.mode_utilisateur' => $mode 
		) )->order ( "id_utilisateur desc" );
		if (! is_null ( $limit ) && ! is_null ( $offset )) {
			$select->limit ( $limit )->offset ( $offset );
		}
		$statement = $sql->prepareStatementForSqlObject ( $select );
		$rows_results = $statement->execute ();
		return $rows_results;
	}
	public function getNBListUtilisateurFranchisesByType($type) {
		$sql = new Sql ( $this->tableGateway->getAdapter () );
		$select = $sql->select ()->columns(array('nb'=>new \Zend\Db\Sql\Expression('COUNT(*)')))->from ( $this->tableGateway->getTable () )->where ( array (
				new IsNotNull ( 'type_utilisateur_franchise' ) 
		) );
		$select->where ( array (
				'type_utilisateur_franchise' => $type 
		) );
		
		$statement = $sql->prepareStatementForSqlObject($select);
		$rows_results = $statement->execute()->current();
		return $rows_results['nb'];
		
	}
	public function getListUtilisateurFranchisesByType($type, $limit = null, $offset = null) {
		$rows_results = array ();
		$sql = new Sql ( $this->tableGateway->getAdapter () );
		$select = $sql->select ()->columns ( array (
				'id_utilisateur',
				'nom_utilisateur',
				'prenom_utilisateur',
				'email_utilisateur',
				'date_demande_inscription',
				'date_inscription_reelle',
				'plateforme_inscription',
				'type_utilisateur_franchise',
				'mode_utilisateur' 
		) )->from ( $this->tableGateway->getTable () )->where ( array (
				new IsNotNull ( 'type_utilisateur_franchise' ) 
		) );
		$select->where ( array (
				'utilisateurs.type_utilisateur_franchise' => $type 
		) )->order ( "id_utilisateur desc" );
		if (! is_null ( $limit ) && ! is_null ( $offset )) {
			$select->limit ( $limit )->offset ( $offset );
		}
		$statement = $sql->prepareStatementForSqlObject ( $select );
		$rows_results = $statement->execute ();
		return $rows_results;
	}
	public function getNBListUtilisateurFranchisesByModeAndType($mode, $type) {
		$sql = new Sql ( $this->tableGateway->getAdapter () );
		$select = $sql->select ()->columns(array('nb'=>new \Zend\Db\Sql\Expression('COUNT(*)')))->from ( $this->tableGateway->getTable () )
		->where (  array (
				new IsNotNull ( 'type_utilisateur_franchise' ) 
		) );
		$select->where ( array (
				'utilisateurs.mode_utilisateur' => $mode,
				'type_utilisateur_franchise' => $type 
		) );
		
		$statement = $sql->prepareStatementForSqlObject($select);
		$rows_results = $statement->execute()->current();
		return $rows_results['nb'];
		
	}
	public function getListUtilisateurFranchisesByModeAndType($mode, $type, $limit = null, $offset = null) {
		$rows_results = array ();
		$sql = new Sql ( $this->tableGateway->getAdapter () );
		$select = $sql->select ()->columns ( array (
				'id_utilisateur',
				'nom_utilisateur',
				'prenom_utilisateur',
				'email_utilisateur',
				'date_demande_inscription',
				'date_inscription_reelle',
				'plateforme_inscription',
				'type_utilisateur_franchise',
				'mode_utilisateur' 
		) )->from ( $this->tableGateway->getTable () )->where ( array (
				new IsNotNull ( 'type_utilisateur_franchise' ) 
		) );
		$select->where ( array (
				'utilisateurs.mode_utilisateur' => $mode,
				'type_utilisateur_franchise' => $type 
		) )->order ( "id_utilisateur desc" );
		if (! is_null ( $limit ) && ! is_null ( $offset )) {
			$select->limit ( $limit )->offset ( $offset );
		}
		$statement = $sql->prepareStatementForSqlObject ( $select );
		$rows_results = $statement->execute ();
		return $rows_results;
	}
	public function getNumberInscrits($dateBegin, $dateEnd) {
		$rows_results = array ();
		$sql = new Sql ( $this->tableGateway->getAdapter () );
		$select = $sql->select ()->columns ( array (
				'id_utilisateur' 
		) )->from ( $this->tableGateway->getTable () );
		$predicate = new \Zend\Db\Sql\Where ();
		$select->where ( $predicate->greaterThanOrEqualTo ( 'date_inscription_reelle', $dateBegin ) );
		$select->where ( $predicate->lessThanOrEqualTo ( 'date_inscription_reelle', $dateEnd ) );
		$select->where ( $predicate->isNotNull ( 'type_utilisateur_franchise' ) );
		$statement = $sql->prepareStatementForSqlObject ( $select );
		$rows_results = $statement->execute ();
		return count ( $rows_results );
	}
}