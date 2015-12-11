<?php

namespace Model\Custom;

/**
 *
 * @author Sandeepn
 *        
 */
class Error {
	
	/**
	 *
	 * @param unknown $e        	
	 * @param unknown $params        	
	 */
	public static function trigger($e, $params) {
		if (DEBUG)
			throw new \Exception ( $e->getMessage () );
		else
			return false;
	}
}