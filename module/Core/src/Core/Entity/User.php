<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use ZfcUser\Entity\UserInterface;

/**
 * An example of how to implement a role aware user entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class User implements UserInterface {
	const ROLE_GUEST = 'guest';
	const ROLE_ADMIN = 'admin';
	const ROLE_SOUSTRAITANT = 'soustraitant';
	const ROLE_SUPERUSER = 'superuser';
	protected $inputFilter;
	/**
	 *
	 * @var int @ORM\Id
	 *      @ORM\Column(type="integer")
	 *      @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 *
	 * @var string @ORM\Column(type="string", length=255, unique=true, nullable=true)
	 */
	protected $username;
	
	/**
	 *
	 * @var string @ORM\Column(type="string", unique=true, length=255)
	 */
	protected $email;
	
	/**
	 *
	 * @var string @ORM\Column(name="display_name",type="string", length=50, nullable=true)
	 */
	protected $displayName;
	
	/**
	 *
	 * @var string @ORM\Column(type="string", length=128)
	 */
	protected $password;
	
	/**
	 *
	 * @var string @ORM\Column(type="string", length=50, nullable=true)
	 */
	protected $role;
	
	/**
	 *
	 * @var string @ORM\Column(type="string", length=50, nullable=true)
	 */
	protected $state;
	
	/**
	 * Get id.
	 *
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Set id.
	 *
	 * @param int $id        	
	 *
	 * @return void
	 */
	public function setId($id) {
		$this->id = ( int ) $id;
	}
	
	/**
	 * Get username.
	 *
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}
	
	/**
	 * Set username.
	 *
	 * @param string $username        	
	 *
	 * @return void
	 */
	public function setUsername($username) {
		$this->username = $username;
	}
	
	/**
	 * Get email.
	 *
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}
	
	/**
	 * Set email.
	 *
	 * @param string $email        	
	 *
	 * @return void
	 */
	public function setEmail($email) {
		$this->email = $email;
	}
	
	/**
	 * Get displayName.
	 *
	 * @return string
	 */
	public function getDisplayName() {
		return $this->displayName;
	}
	
	/**
	 * Set displayName.
	 *
	 * @param string $displayName        	
	 *
	 * @return void
	 */
	public function setDisplayName($displayName) {
		$this->displayName = $displayName;
	}
	
	/**
	 * Get password.
	 *
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}
	
	/**
	 * Set password.
	 *
	 * @param string $password        	
	 *
	 * @return void
	 */
	public function setPassword($password) {
		$this->password = $password;
	}
	
	/**
	 * Get roles.
	 *
	 * @return array
	 */
	public function getRole() {
		return $this->role;
	}
	public function setRole($role) {
		$this->role = $role;
	}
	
	/**
	 * Get state.
	 *
	 * @return int
	 */
	public function getState() {
		return $this->state;
	}
	
	/**
	 * Set state.
	 *
	 * @param int $state        	
	 * @return UserInterface
	 */
	public function setState($state) {
		$this->state = $state;
	}
	
	/**
	 * Set input method
	 *
	 * @param InputFilterInterface $inputFilter        	
	 */
	public 

	function setInputFilter(InputFilterInterface $inputFilter) {
		throw new \Exception ( "Non utilisé" );
	}
	
	/**
	 * Get input filter
	 *
	 * @return InputFilterInterface
	 */
	public function getInputFilter() {
		if (! $this->inputFilter) {
			$inputFilter = new InputFilter ();
			$factory = new InputFactory ();
			$inputFilter->add ( $factory->createInput ( array (
					'name' => 'username',
					'required' => true,
					'filters' => array (
							array (
									'name' => 'StringTrim' 
							) 
					),
					'validators' => array (
							array (
									'name' => 'not_empty' 
							),
							array (
									'name' => 'StringLength',
									'options' => array (
											'encoding' => 'UTF-8',
											'min' => 1,
											'max' => 255 
									) 
							) 
					) 
			) ) );
			
			$inputFilter->add ( $factory->createInput ( array (
					'name' => 'email',
					'required' => true,
					'filters' => array (
							array (
									'name' => 'StringTrim' 
							) 
					),
					'validators' => array (
							array (
									'name' => 'not_empty' 
							),
							array (
									'name' => 'StringLength',
									'options' => array (
											'encoding' => 'UTF-8',
											'min' => 1,
											'max' => 255 
									) 
							) 
					) 
			) ) );
			
			$inputFilter->add ( $factory->createInput ( array (
					'name' => 'password',
					'required' => true,
					'filters' => array (
							array (
									'name' => 'StringTrim' 
							) 
					),
					'validators' => array (
							array (
									'name' => 'not_empty' 
							),
							array (
									'name' => 'StringLength',
									'options' => array (
											'encoding' => 'UTF-8',
											'min' => 6,
											'max' => 255 
									) 
							) 
					) 
			) ) );
			
			$this->inputFilter = $inputFilter;
		}
		
		return $this->inputFilter;
	}
}