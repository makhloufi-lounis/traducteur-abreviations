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
class IndexController extends AbstractActionController
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    protected $abreviationTable;
    

    public function indexAction()
    {
        $paginator = $this->getAbreviationTable()->fetchAll(true);
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        $paginator->setItemCountPerPage(10);
        return new ViewModel(array(
            'paginator' => $paginator,
        	'route'	=> 'home',
        ));

        //test

        //test
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