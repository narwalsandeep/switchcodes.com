<?php

namespace Kernel\Db;

/**
 *
 * @author Sandeepn
 *        
 */
class Metadata implements \Zend\ServiceManager\ServiceLocatorAwareInterface {
	
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
	 */
	public function getTableColumns($tableName) {
		$sm = $this->getServiceLocator ();
		$adapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
		$metadata = new \Zend\Db\Metadata\Metadata ( $this->adapter );
		
		$table = $metadata->getTable ( $tableName );
		return $table->getColumns ();
	}
}