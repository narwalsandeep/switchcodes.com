<?php

namespace Component\Entropy;

/**
 *
 * @author Sandeepn
 *        
 */
class Entropy {
	
	/**
	 *
	 * @param unknown $e        	
	 * @param unknown $params        	
	 */
	public static function getNewHash($token) {
		return sha1 ( APP_NAME . $token . microtime () );
	}
}


