<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Storage\SessionStorage;
use Model\Entity\User;

/**
 *
 * @author sandeepnarwal
 *        
 */
class SigninController extends AbstractActionController {
	/*
	 * (non-PHPdoc)
	 * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
	 */
	public function indexAction() {
	}
	
	/**
	 *
	 * @return Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>
	 */
	public function processAction() {
		$username = $this->params ()->fromPost ( 'username' );
		$password = $this->params ()->fromPost ( 'passwd' );
		
		$adapterService = $this->getServiceLocator ()->get ( 'Zend\Db\Adapter\Adapter' );
		$authService = new \Zend\Authentication\AuthenticationService ();
		
		if (! trim ( $username ) || ! trim ( $password )) {
			// clear identity anyway
			$authService->clearIdentity ();
			return $this->redirect ()->toRoute ( 'application/child', array (
				'controller' => 'signin',
				'action' => 'index' 
			) );
		}
		
		$adapter = new \Zend\Authentication\Adapter\DbTable ( $adapterService, 'energy_user', 'username', 'passwd' );
		$authService->setAdapter ( $adapter );
		$authService->getAdapter ()->setIdentity ( $username )->setCredential ( $password );
		$result = $authService->authenticate ();
		
		if ($result->isValid ()) {
			$UserTable = $this->getServiceLocator ()->get ( 'Model\Entity\User' );
			$UserData = $UserTable->getFinder ()->setParams ( array (
				"where" => array (
					"username" => $username 
				) 
			) )->findOne ();
			if ($UserData) {
				if ($UserData->status == User::ACTIVE) {
					
					// now write auth into session, but not password
					$UserData->passwd = NULL;
					$UserData->auth_token = NULL;
					$authService->getStorage ()->write ( $UserData );
					
					if ($authService->hasIdentity ()) {
						
						// type must be a valid type to login
						switch ($UserData->user_type) {
							case User::SU :
								$this->flashMessenger ()->addMessage ( array (
									'success' => 'Logged in as Super User.' 
								) );
								return $this->redirect ()->toRoute ( 'su' );
							case User::CUSTOMER :
								$this->flashMessenger ()->addMessage ( array (
									'success' => 'You are successfully logged in.' 
								) );
								return $this->redirect ()->toRoute ( 'user' );
							case 'default' :
								$this->flashMessenger ()->addMessage ( array (
									'error' => 'Cannot Identify User.' 
								) );
						}
					} else {
						$this->flashMessenger ()->addMessage ( array (
							'error' => 'Server error occurred.' 
						) );
					}
				} else {
					$this->flashMessenger ()->addMessage ( array (
						'error' => 'Cannot Login. Please check account status.' 
					) );
				}
			}
		} else {
			$this->flashMessenger ()->addMessage ( array (
				'error' => 'Invalid Username/Password' 
			) );
		}
		
		// clear identity, just in case of bug
		$authService->clearIdentity ();
		return $this->redirect ()->toRoute ( 'application/child', array (
			'controller' => 'signin',
			'action' => 'quit' 
		) );
	}
	
	/**
	 *
	 * @return Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>
	 */
	public function quitAction() {
		$authService = new \Zend\Authentication\AuthenticationService ();
		$authService->clearIdentity ();
		
		return $this->redirect ()->toRoute ( 'application/child', array (
			'controller' => 'signin',
			'action' => 'index' 
		) );
	}
}
