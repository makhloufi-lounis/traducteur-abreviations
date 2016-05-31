<?php

namespace Core\Form;

use Core\Entity\User;
use Zend\Form\Element;
use Zend\Form\Form;

class UserForm extends Form {
	public function __construct($name = null) {
		parent::__construct ( 'user_form' );
		
		$this->add ( array (
				'name' => 'username',
				'type' => 'Zend\Form\Element\Text',
				'options' => array (
						'label' => 'Username *',
						'column-size' => 'sm-4',
						'label_attributes' => array (
								'class' => 'col-sm-2' 
						) 
				),
				
				'attributes' => array (
						'placeholder' => 'Username',
						'id' => 'username',
						'autocomplete' => 'off' 
				) 
		) );
		
		$this->add ( array (
				'name' => 'email',
				'type' => 'Zend\Form\Element\Email',
				'options' => array (
						'label' => 'Email *',
						'column-size' => 'sm-4',
						'label_attributes' => array (
								'class' => 'col-sm-2' 
						) 
				),
				
				'attributes' => array (
						'placeholder' => 'Email',
						'id' => 'email',
						'autocomplete' => 'off' 
				) 
		) );
		
		$this->add ( array (
				'name' => 'password',
				'type' => 'Zend\Form\Element\Password',
				'options' => array (
						'label' => 'Password *',
						'column-size' => 'sm-4',
						'label_attributes' => array (
								'class' => 'col-sm-2' 
						) 
				),
				'attributes' => array (
						'placeholder' => 'Password',
						'id' => 'password' 
				) 
		) );
		
		$this->add ( array (
				'name' => 'role',
				'type' => 'Zend\Form\Element\Radio',
				'options' => array (
						'label' => 'Role *',
						'column-size' => 'sm-4',
						'label_attributes' => array (
								'class' => 'col-sm-2' 
						),
						'value_options' => array (
								User::ROLE_GUEST => 'Guest',
								User::ROLE_SOUSTRAITANT => 'Soustraitant',
								User::ROLE_ADMIN => 'Administrateur',
								User::ROLE_SUPERUSER => 'Superuser' 
						) 
				) 
		) );
		
		$this->add ( array (
				'name' => 'soumettre',
				'type' => 'Zend\Form\Element\Button',
				'attributes' => array (
						'type' => 'submit',
						'value' => 'Sauvegarder',
						'class' => 'btn btn-primary',
				),
				'options' => array (
						'label' => 'Valider',
						'column-size' => 'sm-10 col-sm-offset-5' 
				) 
		) );
	}
}