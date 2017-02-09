<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application\Controller;

use Application\Controller\EntityUsingController;
use Application\Entity\User;
use Application\Form\UserForm;
use Application\Service\Encryptage\EncryptageStrategyInterface;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Zend\View\Model\ViewModel;

class AclManagerController extends EntityUsingController {
	public function indexAction() {
		$view = new ViewModel ( array () );
		$em = $this->getEntityManager ();
		$repository = $em->getRepository ( 'Application\Entity\User' );
		$view->users = $repository->findBy ( array (), array (
				'id' => 'asc' 
		) );
		return $view;
	}
	
	
	//test
	public function addAction() {
		return $this->editAction ();
	}
	public function editAction() {
		if ($this->params ( 'id' )) {
			$user = $this->getEntityManager ()->getRepository ( 'Application\Entity\User' )->find ( ( int ) $this->params ( 'id' ) );
			if (is_null ( $user )) {
				$this->redirect ()->toRoute ( 'admin/erreur', array (
						'action' => 'notFound' 
				) );
				$this->getResponse ()->sendHeaders ();
				exit ();
			}
		} else {
			$user = new User ();
		}
		$form = new UserForm ();
		$form->setHydrator ( new DoctrineEntity ( $this->getEntityManager (), 'Application\Entity\User' ) );
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
				return $this->redirect ()->toRoute ( 'admin/aclmanager' );
			}
		}
		
		return new ViewModel ( array (
				'user' => $user,
				'form' => $form 
		) );
	}
}
