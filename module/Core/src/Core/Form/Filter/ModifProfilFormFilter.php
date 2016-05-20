<?php

namespace Core\Form\Filter;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Form\Annotation\Validator;

class ModifProfilFormFilter implements InputFilterAwareInterface {
	protected $inputFilter;
	public function setInputFilter(InputFilterInterface $inputFilter) {
		throw new \Exception ( "Not used" );
	}
	public function getInputFilter() {
		if (! $this->inputFilter) {
			$inputFilter = new InputFilter ();
			$factory = new InputFactory ();
			
			$inputFilter->add ( $factory->createInput ( array (
					'name' => 'typeUtilisateur',
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
					'name' => 'anneeNaissance',
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
					'name' => 'nom',
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
					'name' => 'prenom',
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
					'name' => 'telephone',
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
			
			
			$inputFilter->add ( $factory->createInput ( array (
					'name' => 'email',
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
			
			
			
			$this->inputFilter = $inputFilter;
		}
		
		return $this->inputFilter;
	}
}
?>