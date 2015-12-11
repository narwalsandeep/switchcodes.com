<?php

namespace Component\Auth;

use Zend\Authentication\AuthenticationService;

/**
 *
 * @author Sandeepn
 *        
 */
class Auth {
	
	/**
	 *
	 * @return \Zend\Authentication\AuthenticationService
	 */
	private static function _getAuthObject() {
		$Auth = new AuthenticationService ();
		$Auth->setStorage ( new \Zend\Authentication\Storage\Session ( (SESSION_KEY) ? SESSION_KEY : "" ) );
		return $Auth;
	}
	
	/**
	 *
	 * @param unknown $data        	
	 */
	public static function setUser($data) {
		if (SESSION_KEY == "") {
			die ( "You must define some random SESSION_KEY in Index.php" );
		}
		$auth = new AuthenticationService ();
		$auth->setStorage ( new \Zend\Authentication\Storage\Session ( (SESSION_KEY) ? SESSION_KEY : "" ) );
		$auth->getStorage ()->write ( $data );
	}
	
	/**
	 *
	 * @return boolean
	 */
	public static function getUser() {
		$AuthStorage = null;
		$Auth = self::_getAuthObject ();
		if ($Auth->hasIdentity ()) {
			return $Auth->getStorage ()->read ();
		}
		return false;
	}
	
	/**
	 *
	 * @return boolean
	 */
	public static function getStorage() {
		$AuthStorage = null;
		$Auth = self::_getAuthObject ();
		if ($Auth->hasIdentity ()) {
			return $Auth->getStorage ();
		}
		return false;
	}
	
	/**
	 *
	 * @return boolean
	 */
	public static function isLoggedIn() {
		$AuthStorage = null;
		$Auth = self::_getAuthObject ();
		if ($Auth->hasIdentity ()) {
			return true;
		}
		return false;
	}
	
	/**
	 */
	public static function logout() {
		self::_getAuthObject ()->clearIdentity ();
	}
}