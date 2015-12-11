<?php

namespace Kernel\Db;

/**
 *
 * @author Sandeepn
 *        
 */
class Entity {
	
	/**
	 *
	 * @var unknown
	 */
	public $id;
	
	/**
	 *
	 * @param unknown $data        	
	 */
	public function exchangeArray($data) {
		// if nothing was sent, do not proceed at all
		if (! $data) {
			return false;
		}
		
		foreach ( $data as $key => $value ) {
			$this->{$key} = $value;
		}
		
		if ($this->id)
			$this->updated = time ();
		else
			$this->created = time ();
		return $this;
	}
	
	/**
	 *
	 * @param unknown $func        	
	 * @param unknown $args        	
	 * @return unknown
	 */
	public function __call($func, $args) {
		if ($func == "prepareSave") {
			return $args [0];
		} else {
			die ( "Invalid method name: $func" );
		}
	}
}
