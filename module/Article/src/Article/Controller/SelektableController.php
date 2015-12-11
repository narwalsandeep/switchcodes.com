<?php

namespace Article\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 *
 * @author Sandeepn
 *
 */
class SelektableController extends AbstractActionController {

	/**
	 * (non-PHPdoc)
	 * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
	 */
	public function indexAction() {
		return $this->redirect ()->toRoute ( "article", array (
			"controller" => "selektable",
			"action" => "read"
		) );
	}

	/**
	 *
	 */
	public function tryAction() {
	}

	/**
	 *
	 */
	public function readAction() {
	}
}
