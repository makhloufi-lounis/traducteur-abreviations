<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Core\Entity\User;
use Core\Form\UserForm;
use Zend\ServiceManager\ServiceLocatorInterface;
use Core\Service\Encryptage\EncryptageStrategyInterface;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Zend\View\Model\ViewModel;

class UserManagementController extends AbstractActionController
{
	/**
	 * @var ServiceLocatorInterface
	 */
	protected $serviceLocator;
	
	
	public function indexAction() {

		$view = new ViewModel ( array () );
		$em = $this->serviceLocator->get ( 'doctrine.entitymanager.orm_default' );

		$repository = $em->getRepository ( 'Core\Entity\User' );

		$view->users = $repository->findBy ( array (), array (
				'id' => 'asc' 
		) );
		return $view;
	}
	public function addAction() {
		return $this->editAction ();
	}
	public function editAction() {
		$em = $this->serviceLocator->get ( 'doctrine.entitymanager.orm_default' );
		if ($this->params ( 'id' )) {
			$user = $em->getRepository ( 'Core\Entity\User' )->find ( ( int ) $this->params ( 'id' ) );
			if (is_null ( $user )) {
				$this->redirect ()->toRoute ( 'application/erreur', array (
						'action' => 'notFound' 
				) );
				$this->getResponse ()->sendHeaders ();
				exit ();
			}
		} else {
			$user = new User ();
		}
		$form = new UserForm ();
		$form->setHydrator ( new DoctrineEntity ( $em , 'Core\Entity\User' ) );
		$form->bind ( $user );
		
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$form->setInputFilter ( $user->getInputFilter () );
			$form->setData ( $request->getPost () );
			$encryptage = $this->getServiceLocator ()->get ( "zfc-user-password-encryptage" );
			if ($encryptage instanceof EncryptageStrategyInterface) {
				$password = $encryptage->encryptage ( $request->getPost ( 'password' ) );
			}
			if ($form->isValid ()) {
				$em = $this->getEntityManager ();
				$user->setPassword ( $password );
				$em->persist ( $user );
				$em->flush ( $user );
				return $this->redirect ()->toRoute ( 'application/aclmanager' );
			}
		}
		
		return new ViewModel ( array (
				'user' => $user,
				'form' => $form 
		) );
	}
}
