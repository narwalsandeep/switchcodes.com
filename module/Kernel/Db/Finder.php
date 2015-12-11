<?php

namespace Kernel\Db;

use Zend\Db\TableGateway\TableGateway;

class Finder {
	
	/**
	 *
	 * @var unknown
	 */
	protected $_params = array ();
	
	/**
	 *
	 * @var unknown
	 */
	protected $_paginate;
	
	/**
	 *
	 * @var unknown
	 */
	protected $_current_page;
	
	/**
	 *
	 * @var unknown
	 */
	protected $_items_per_page;
	
	/**
	 *
	 * @var unknown
	 */
	protected $_return_type;
	
	/**
	 *
	 * @var EntityTable
	 */
	protected $_table;
	
	/**
	 *
	 * @param Table $table        	
	 */
	public function __construct(Table $table) {
		$this->_table = $table;
	}
	
	/**
	 *
	 * @param unknown $func        	
	 * @param unknown $args        	
	 */
	public function __call($func, $args) {
		$class = "\Kernel\Db\Extended\\" . ucfirst ( str_replace ( "_", "", $this->_table->tableGateway->table ) );
		$new = new $class ( $this->getSelect (), $this->_table->tableGateway );
		return $new->{$func} ();
	}
	
	/**
	 *
	 * @param string $flag        	
	 * @return \Model\Entity\EntityFinder
	 */
	public function setPagination($flag = false) {
		$this->_paginate = $flag;
		return $this;
	}
	
	/**
	 *
	 * @return \Model\Entity\unknown
	 */
	public function getPagination() {
		return $this->_paginate;
	}
	
	/**
	 *
	 * @param unknown $page        	
	 * @return \Model\Entity\EntityFinder
	 */
	public function setCurrentPage($page) {
		$this->_current_page = $page;
		return $this;
	}
	
	/**
	 *
	 * @param unknown $itemsPerPage        	
	 * @return \Model\Entity\EntityFinder
	 */
	public function setItemsPerPage($itemsPerPage) {
		$this->_items_per_page = $itemsPerPage;
		return $this;
	}
	
	/**
	 */
	public function getSql() {
		return $this->_table->tableGateway->getSql ();
	}
	
	/**
	 */
	public function getSelect() {
		return $this->_table->tableGateway->getSql ()->select ();
	}
	
	/**
	 *
	 * @return boolean
	 */
	public function find($params = null) {
		$select = $this->getSql ()->select ();
		
		try {
			if (isset ( $params ['sort'] ))
				$select->order ( $params ['sort'] );
			
			if (isset ( $params ['group'] ))
				$select->group ( $params ['group'] );
			
			if (isset ( $params ['having'] ))
				$select->having ( $params ['having'] );
			
			if (! isset ( $params ['where'] ))
				$params ['where'] = array ();
			
			foreach ( $params ['where'] as $field => $value ) {
				$select->where->equalTo ( $field, $value );
			}
			
			if ($params ['one']) {
				$select->limit ( 1 );
			}
			
			echo $this->_table->tableGateway->getSql ()->getSqlstringForSqlObject ( $select ) . "<br>";
			$record = $this->_table->tableGateway->selectWith ( $select );
			if ($record->count () < 1) {
				$record = false;
			}
		} catch ( \Exception $e ) {
			die ( $e->getMessage () );
		}
		
		if ($params ['one']) {
			if ($record) {
				return $record->current ();
			} else {
				return false;
			}
		} else {
			return $record;
		}
	}
	
	/**
	 *
	 * @param unknown $select        	
	 * @return \Zend\Paginator\Paginator
	 */
	public function getPaginator($select) {
		$Paginator = new \Zend\Paginator\Paginator ( new \Zend\Paginator\Adapter\Iterator ( $select ) );
		$Paginator->setCurrentPageNumber ( $this->_current_page );
		$Paginator->setItemCountPerPage ( $this->_items_per_page );
		return $Paginator;
	}
}
