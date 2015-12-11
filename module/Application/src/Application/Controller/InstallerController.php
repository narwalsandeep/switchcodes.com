<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 *
 * @author sandeepnarwal
 *        
 */
class InstallerController extends AbstractActionController {
	/*
	 * (non-PHPdoc)
	 * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
	 */
	public function indexAction() {
		$this->layout ( "layout/install" );
		$view = new ViewModel ();
		return $view;
	}
	
	/**
	 */
	public function startAction() {
		$this->adapter = new \Zend\Db\Adapter\Adapter ( array (
			'driver' => 'mysqli',
			'host' => ($params ['host'] == "") ? "localhost" : $params ['host'],
			'database' => $params ['dbname'],
			'username' => $params ['username'],
			'password' => $params ['password'] 
		) );
		
		echo DOC_ROOT . "/config/schema/ddl.php";
		$ddl = file_get_contents ( DOC_ROOT . "/config/schema/ddl.php" );
		$ddl_sequence = explode ( ";", $ddl );
		
		print_r($ddl_sequence);
		$error = false;
		foreach ( $ddl_sequence as $key => $value ) {
			echo $value;
			//$this->adapter->query ( $value );
		}
		
		die;
	}
}
