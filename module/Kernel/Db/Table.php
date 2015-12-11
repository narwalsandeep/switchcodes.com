<?php

namespace Kernel\Db;

use Zend\Db\TableGateway\TableGateway;

/**
 * Every table must extend this, it provides gateway and other relevant db tasks
 * for common CRUD or search, count, fetch task, extend here
 * This should NOT contain table specific business logics or App specific business logics
 *
 * @author sandeepnarwal
 *        
 */
abstract class Table implements \Zend\ServiceManager\ServiceLocatorAwareInterface {
	
	/**
	 *
	 * @var unknown
	 */
	protected $serviceLocator;
	
	/**
	 *
	 * @var TableGateway
	 */
	public $tableGateway;
	
	/**
	 *
	 * @param TableGateway $tableGateway        	
	 */
	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}
	
	/**
	 *
	 * @return unknown
	 */
	public function getFinder() {
		$class = get_class ( $this );
		
		// to remove "Table" in end of class name
		$class = substr ( $class, 0, strlen ( $class ) - 5 );
		$class = $class . "Finder";
		return new $class ( $this );
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
	 * @return TableGateway
	 */
	public function getTableGateway() {
		return $this->tableGateway;
	}
	
	/**
	 *
	 * @throws \Exception
	 * @return number
	 */
	public function save($entity, $where = null) {
		
		// if $entity do not exists, return false
		if ($entity == false) {
			return false;
		}
		
		$table = $this->getTableGateway ()->table;
		$columns = $this->getColumns ();
		foreach ( $columns as $key => $value ) {
			if (property_exists ( $entity, $value ))
				$data [$value] = $entity->{$value};
		}
		
		// if id is set then also we need to update
		// create $where based on id in such a case
		try {
			
			if (! is_array ( $where )) {
				
				if ($data ['id'] > 0) {
					$where = array (
						"id" => $data ['id'] 
					);
					
					$this->tableGateway->update ( $data, $where );
					return $data ['id'];
				} else {
					// insert and return last ID
					$this->tableGateway->insert ( $data );
					return $this->tableGateway->lastInsertValue;
				}
			} else {
				$this->tableGateway->update ( $data, $where );
				return $data ['id'];
			}
		} catch ( \Exception $e ) {
			
			die ( $e->getMessage () );
		}
	}
	
	/**
	 * delete by id of the table or array condition
	 *
	 * @param int/array $id        	
	 */
	public function delete($id) {
		try {
			if (is_array ( $id )) {
				$effectedRows = $this->tableGateway->delete ( $id );
			} else {
				$effectedRows = $this->tableGateway->delete ( array (
					'id' => ( int ) $id 
				) );
			}
		} catch ( \Exception $e ) {
			return \Model\Custom\Error::trigger ( $e, $params );
		}
		
		return $effectedRows;
	}
}
