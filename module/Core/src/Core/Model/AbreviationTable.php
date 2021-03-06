<?php
namespace Core\Model;

 use Zend\Db\TableGateway\TableGateway;
 use Zend\Db\ResultSet\ResultSet;
 use Zend\Db\Sql\Select;
 use Zend\Paginator\Adapter\DbSelect;
 use Zend\Paginator\Paginator;

 class AbreviationTable
 {
     protected $tableGateway;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }

     public function fetchAll($paginated=false)
     {
        if ($paginated) {
             
             $select = new Select('abreviations');
             
             $resultSetPrototype = new ResultSet();
             $resultSetPrototype->setArrayObjectPrototype(new Abreviation());
             
             $paginatorAdapter = new DbSelect(
             
                 $select,
                 
                 $this->tableGateway->getAdapter(),
                
                 $resultSetPrototype
             );
             $paginator = new Paginator($paginatorAdapter);
             return $paginator;
         }
         $resultSet = $this->tableGateway->select();
         return $resultSet;
     }

     public function getAbreviation($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Impossible de trouver la ligne $id");
         }
         return $row;
     }
     
     public function searchAbreviationsFromLetter($letter, $paginated=false){
         if ($paginated) {
             
             $select = new Select('abreviations');
             if(!empty($letter)){
             	$select->where->like('iteme', $letter."%");
             }else{
             	$select->where->equalTo('iteme', "");
             }
             
             $resultSetPrototype = new ResultSet();
             $resultSetPrototype->setArrayObjectPrototype(new Abreviation());
            
             $paginatorAdapter = new DbSelect(
             
                 $select,
                 
                 $this->tableGateway->getAdapter(),
                 
                 $resultSetPrototype
             );
             $paginator = new Paginator($paginatorAdapter);
             return $paginator;
         }
	     	$rowset = $this->tableGateway->select(function (Select $select) use ($letter) {
			     $select->where->like('iteme', $letter."%");
			});
	     	if (count($rowset) == 0) {
	     		throw new \Exception("Il ya aucun iteme qui commance par cette lettre");
	     	}
	     	return $rowset;     	
     }
     
     public function searchAbreviationsFromCritereDeRecherche($critere_recherche, $mot_cle, $paginated=false){
     	
     	if ($paginated) {
     		     		 
     		$select = new Select('abreviations');
     		if($critere_recherche == 'iteme'){
     			$select->where(array('iteme' => $mot_cle));
     		}
	     	if($critere_recherche == 'terme'){
	     		$select->where(array('traduction' => $mot_cle));
	     	}
     		 
     		$resultSetPrototype = new ResultSet();
     		$resultSetPrototype->setArrayObjectPrototype(new Abreviation());
     	
     		$paginatorAdapter = new DbSelect(
     				 
     				$select,
     				 
     				$this->tableGateway->getAdapter(),
     				 
     				$resultSetPrototype
     				);
     		$paginator = new Paginator($paginatorAdapter);
     		return $paginator;
     	}
     	
     	if($critere_recherche == 'iteme'){
     		$rowset = $this->tableGateway->select(array('iteme' => $mot_cle));
     	}
     	
     	if($critere_recherche == 'terme'){
     		$rowset = $this->tableGateway->select(array('traduction' => $mot_cle));
     	}
     	
     	if (count($rowset) == 0) {
     		throw new \Exception("Il ya aucun iteme ou traduction qui correspond a votre recherche dans le dictionaire");
     	}
     	
     	return $rowset;
     	
     }

     public function saveAbreviation(Abreviation $abreviation)
     {
         $data = array(
             'iteme' => $abreviation->iteme,
             'traduction'  => $abreviation->traduction,
         );

         $id = (int) $abreviation->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->getAbreviation($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception('Abreviation n\'existe pas');
             }
         }
     }

     public function deleteAbreviation($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }
 }