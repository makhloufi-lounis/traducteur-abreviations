<?php

namespace Core\Form\Filter;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Form\Annotation\Validator;

class InscriptionFormFilter implements InputFilterAwareInterface {
	protected $inputFilter;
	public function setInputFilter(InputFilterInterface $inputFilter) {
		throw new \Exception ( "Not used" );
	}
	public function getInputFilter() {
		if (! $this->inputFilter) {
			$inputFilter = new InputFilter ();
			$factory = new InputFactory ();
			
			$inputFilter->add ( $factory->createInput ( array (
					'name' => 'type_utilisateur_franchise',
					'required' => true,
					'validators' => array (
							array (
									'name' => 'NotEmpty',
									'options' => array (
											'messages' => array (
													\Zend\Validator\NotEmpty::IS_EMPTY => 'Vous devez préciser le type d\'utilisateur' 
											) 
									) 
							) 
					) 
			) ) );
			
			$inputFilter->add ( $factory->createInput ( array (
					'name' => 'annee_naissance',
					'required' => true,
					'validators' => array (
							array (
									'name' => 'NotEmpty',
									'options' => array (
											'messages' => array (
													\Zend\Validator\NotEmpty::IS_EMPTY => 'Veuillez sélectionner votre année de naissance' 
											) 
									) 
							) 
					) 
			) ) );
			
			$inputFilter->add ( $factory->createInput ( array (
					'name' => 'civilite',
					'required' => true,
					'validators' => array (
							array (
									'name' => 'NotEmpty',
									'options' => array (
											'messages' => array (
													\Zend\Validator\NotEmpty::IS_EMPTY => 'Vous devez préciser votre civilité' 
											) 
									) 
							) 
					) 
			) ) );
			
			$inputFilter->add ( $factory->createInput ( array (
					'name' => 'nom_utilisateur',
					'required' => true,
					'validators' => array (
							array (
									'name' => 'NotEmpty',
									'options' => array (
											'messages' => array (
													\Zend\Validator\NotEmpty::IS_EMPTY => 'Le nom doit être renseigné' 
											) 
									) 
							) 
					) 
			) ) );
			$inputFilter->add ( $factory->createInput ( array (
					'name' => 'prenom_utilisateur',
					'required' => true,
					'validators' => array (
							array (
									'name' => 'NotEmpty',
									'options' => array (
											'messages' => array (
													\Zend\Validator\NotEmpty::IS_EMPTY => 'Le prénom doit être renseigné' 
											) 
									) 
							) 
					) 
			) ) );
			
			$inputFilter->add ( $factory->createInput ( array (
					'name' => 'telephone_utilisateur',
					'required' => true,
					'validators' => array (
							array (
									'name' => 'NotEmpty',
									'options' => array (
											'messages' => array (
													\Zend\Validator\NotEmpty::IS_EMPTY => 'Le téléphone doit être renseigné' 
											) 
									) 
							) 
					) 
			) ) );
			
// 			$inputFilter->add ( $factory->createInput ( array (
// 					'name' => 'conditionGenerale',
// 					'required' => true,
// 					'validators' => array (
// 							array (
// 									'name' => 'Identical',
// 									'options' => array (
// 											'token' => '1',
// 											'messages' => array (
// 													\Zend\Validator\Identical::NOT_SAME => 'Vous devez acepter les conditions générales d\'utilisation pour votre inscrire' 
// 											) 
// 									) 
// 							) 
// 					) 
// 			) ) );
			
			$inputFilter->add ( $factory->createInput ( array (
					'name' => 'email_utilisateur',
					'required' => true,
					'validators' => array (
							array (
									'name' => 'NotEmpty',
									'options' => array (
											'messages' => array (
													\Zend\Validator\NotEmpty::IS_EMPTY => 'L\'email doit être renseigné' 
											) 
									) 
							),
							array (
									'name' => 'Regex',
									'options' => array (
											'pattern' => '/^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/',
											'messages' => array (
													\Zend\Validator\Regex::NOT_MATCH => 'Veuillez saisir une adress courriel valide' 
											) 
									),
									'break_chain_on_failure' => true 
							) 
					) 
			) ) );
			
			$inputFilter->add ( $factory->createInput ( array (
					'name' => 'confirmerEmail',
					'required' => true,
					'validators' => array (
							array (
									'name' => 'NotEmpty',
									'options' => array (
											'messages' => array (
													\Zend\Validator\NotEmpty::IS_EMPTY => 'Vous devez confirmer votre Email' 
											) 
									) 
							),
							array (
									'name' => 'Regex',
									'options' => array (
											'pattern' => '/^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/',
											'messages' => array (
													\Zend\Validator\Regex::NOT_MATCH => 'Veuillez saisir une adress courriel valide' 
											) 
									),
									'break_chain_on_failure' => true 
							),
							array (
									'name' => 'Identical',
									'options' => array (
											'token' => 'email_utilisateur',
											'messages' => array (
													\Zend\Validator\Identical::NOT_SAME => 'Vous devez confirmer votre Email' 
											) 
									) 
							) 
					) 
			) ) );
			
			$inputFilter->add ( $factory->createInput ( array (
					'name' => 'password_encrypted_utilisateur',
					'required' => true,
					'validators' => array (
							array (
									'name' => 'NotEmpty',
									'options' => array (
											'messages' => array (
													\Zend\Validator\NotEmpty::IS_EMPTY => 'Le mot de passe doit être renseigné' 
											) 
									) 
							) 
					) 
			) ) );
			
			$inputFilter->add ( $factory->createInput ( array (
					'name' => 'confirmerPassword',
					'required' => true,
					'validators' => array (
							array (
									'name' => 'NotEmpty',
									'options' => array (
											'messages' => array (
													\Zend\Validator\NotEmpty::IS_EMPTY => 'Vous devez confirmer le mot de passe' 
											) 
									) 
							),
							array (
									'name' => 'Identical',
									'options' => array (
											'token' => 'password_encrypted_utilisateur',
											'messages' => array (
													\Zend\Validator\Identical::NOT_SAME => 'Vous devez confirmer le mot de passe' 
											) 
									) 
							) 
					) 
			) ) );
			$inputFilter->add ( $factory->createInput ( array (
					'name' => 'mode_reglement_franchise',
					'required' => false,
			) ) );
			
			$this->inputFilter = $inputFilter;
		}
		
		return $this->inputFilter;
	}
}
?>