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
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceLocatorInterface;
use Core\Model\Abreviation;          
use Core\Form\AbreviationForm;       


class AbreviationController extends AbstractActionController
{
	/**
	 * @var ServiceLocatorInterface
	 */
	protected $serviceLocator;
	
	protected $abreviationTable;
	
    public function indexAction()
    {
        return new ViewModel();
    }
    
	public function addAction() 
	{
        $form = new AbreviationForm();
        $form->get('submit')->setValue('Ajouter');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $abreviation = new Abreviation();
            $form->setInputFilter($abreviation->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $abreviation->exchangeArray($form->getData());
                try {
                    $this->getAbreviationTable()->saveAbreviation($abreviation);
                    return $this->redirect()->toRoute('home');
                }catch (\Exception $e){
                    $flashMessages = $this->flashMessenger()->addMessage("Iteme déjas exixté dans le dictionaire ");
                    return array('abreviationForm' => $form, 'flashMessages' => $flashMessages );
                }
            }
        }
        return array('abreviationForm' => $form);
    }
	
	public function editAction()
	{
		$request = $this->getRequest();
		if ($request->isGet()) {
			$id = (int) $this->params()->fromQuery('id');
			if (!$id) {
				return $this->redirect()->toRoute('addabreviation', array(
						'action' => 'add'
				));
			}
		}else{
			$id = (int) $request->getPost('id');
		}
	
		try {
			$abreviation = $this->getAbreviationTable()->getAbreviation($id);
		}
		catch (\Exception $ex) {
			return $this->redirect()->toRoute('home', array(
					'action' => 'index'
			));
		}
	
		$form  = new AbreviationForm();
		$form->bind($abreviation);
		$form->get('submit')->setAttribute('value', 'Edit');
	
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($abreviation->getInputFilter());
			$form->setData($request->getPost());
	
			if ($form->isValid()) {
				$this->getAbreviationTable()->saveAbreviation($abreviation);
				return $this->redirect()->toRoute('home');
			}
		}
		return array(
				'id' => $id,
				'abreviationForm' => $form,
		);
	}
	
	public function deleteAction()
	{
		$request = $this->getRequest();
		$id = (int) $request->getPost('id');
		if (!$id) {
			return $this->redirect()->toRoute('home');
		}
	
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
	
			if ($del == 'Yes') {
				$id = (int) $request->getPost('id');
				$this->getAbreviationTable()->deleteAbreviation($id);
				echo json_encode(array('status' => 'success'));
				exit;
			}
	
	
		}
	
		echo json_encode(array('status' => 'error', 'message' => 'Erreur de supprission'));
		exit;
	}
	
	public function searchAction()
	{
    	$abreviations = array();
    	$request = $this->getRequest();
    	if($request->isGet()){
	    	$letter = $this->params()->fromQuery('letter');
	    	if(!empty($letter)){
	    		try{
		    		$abreviations = $this->getAbreviationTable()->searchAbreviationsFromLetter($letter);
		    		$model = new ViewModel(array('abreviations' => $abreviations));		    		
	    		}catch (\Exception $e){
	    			$flashMessages = $this->flashMessenger()->addMessage($e->getMessage());
	    			$model = new ViewModel(array('abreviations' => $abreviations, 'flashMessages' => $flashMessages)); 	    			
	    		}
	    		
	    	}else{
	    		$flashMessages = $this->flashMessenger()->addMessage('La requete ne fonctionne pas veuillez réessayer plutard');
	    		$model = new ViewModel(array('abreviations' => $abreviations, 'flashMessages' => $flashMessages));
	    	}
	    	
    	}elseif($request->isPost()){
    		$critere_recherche =  $request->getPost('critere_recherche');
    		$mot_cle = $request->getPost('mot_cle');      		
    		try{
    			$abreviations = $this->getAbreviationTable()->searchAbreviationsFromCritereDeRecherche($critere_recherche, $mot_cle);
    			$model = new ViewModel(array('abreviations' => $abreviations));
    		}catch (\Exception $e){
    			$flashMessages = $this->flashMessenger()->addMessage($e->getMessage());
    			$model = new ViewModel(array('abreviations' => $abreviations, 'flashMessages' => $flashMessages));
    		}
    	}
    	
    	$model->setTemplate('application/index/index');
    	return $model;
    	
    }
    
    public function getAbreviationTable()
    {
    	if (!$this->abreviationTable) {
    		$sm = $this->serviceLocator;
    		$this->abreviationTable = $sm->get('Core\Model\AbreviationTable');
    	}
    	return $this->abreviationTable;
    }
    
    
}
