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
        $abreviations = $this->getAbreviationTable()->fetchAll();
        return new ViewModel(
                array('abreviations' => $abreviations)
        );
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