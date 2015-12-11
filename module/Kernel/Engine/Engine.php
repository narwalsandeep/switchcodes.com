<?php

namespace Kernel\Engine;

use Zend\Mvc\Controller\AbstractActionController;

/**
 *
 * @author Sandeepn
 *        
 */
abstract class Engine extends AbstractActionController {
	
	/**
	 *
	 * @var unknown
	 */
	protected $sm;
	
	/**
	 *
	 * @param unknown $sm        	
	 */
	public function __construct() {
		$this->sm = $this->getServiceLocator ();
	}
	
	/**
	 */
	public function getBasePath() {
		$basePath = $this->getRequest ()->getBasePath ();
		$uri = new \Zend\Uri\Uri ( $this->getRequest ()->getUri () );
		$uri->setPath ( $basePath );
		$uri->setQuery ( array () );
		$uri->setFragment ( '' );
		return $uri->getScheme () . '://' . $uri->getHost () . $uri->getPath ();
	}
	
	/**
	 *
	 * @return \Kernel\Engine\Db
	 */
	public function db($cmd, $params) {
		$db = $this->getServiceLocator ()->get ( 'DbEngine' );
		return $db->run ( $cmd, $params );
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
	 */
	public function getServiceLocator() {
		return $this->serviceLocator;
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
	 */
	public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
	}
	
	/**
	 *
	 * @param unknown $service        	
	 * @return Ambigous <object, multitype:>
	 */
	protected function table($service) {
		return $this->getServiceLocator ()->get ( $service );
	}
	
	/**
	 *
	 * @param unknown $service        	
	 */
	protected function finder($service) {
		return $this->getServiceLocator ()->get ( $service )->getFinder ();
	}
	
	/**
	 *
	 * @param unknown $data        	
	 * @return \Zend\View\Model\ViewModel
	 */
	protected function sendView($data) {
		return new \Zend\View\Model\ViewModel ( $data );
	}
	
	/**
	 *
	 * @return \Zend\View\Model\JsonModel
	 */
	protected function sendJson($data = array(), $flag = true, $code = 200, $extra = array(), $headers = false) {
		$response = $this->getResponse ();
		if ($headers) {
			$response->setHeaders ( $headers );
		}
		
		$response->setStatusCode ( $code );
		$return = array_merge ( array (
			'success' => $flag,
			'data' => $data 
		), $extra );
		
		return $this->returnJson ( $return );
	}
	
	/**
	 *
	 * @param unknown $return        	
	 * @return \Zend\View\Model\JsonModel
	 */
	protected function returnJson($return) {
		return new \Zend\View\Model\JsonModel ( $return );
	}
	
	/**
	 *
	 * @param unknown $call        	
	 */
	protected function process($call, $params = null, $callback = null) {
		$this->p = $params;
		$this->call = $call;
		$callStack = explode ( "::", $this->call );
		$this->class = $callStack [0];
		$this->mode = $callStack [1];
		$this->method = $callStack [2];
		
		$this->data = $this->{$this->mode} ( $this->class )->{$this->method} ( $this->p );
		if ($callback) {
			$temp = array ();
			if ($this->data !== false) {
				if (! $params ['one']) {
					foreach ( $this->data as $key => $value ) {
						$return = $callback ( $value );
						if ($return)
							$temp [] = $return;
					}
				} else {
					$return = $callback ( $this->data );
					if ($return)
						$temp = $return;
				}
				$this->data = $temp;
			}
		}
		
		return $this->data;
	}
}	