<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * 
 * @author Sandeepn
 *
 */
class IndexController extends AbstractActionController
{
    /**
     * (non-PHPdoc)
     * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
     */
	public function indexAction()
    {
    	//$this->getServiceLocator()->get("Model\Entity\User");
    	
    	//$this->redirect()->toUrl("application/signin");
        
    }
}
