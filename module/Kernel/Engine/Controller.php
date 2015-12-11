<?php

namespace Kernel\Engine;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http\Client;

/**
 *
 * @author Sandeepn
 *        
 */
class Controller extends \Kernel\Engine\Engine {
	
	/**
	 */
	public function hit($url, $method = "GET", $params) {
		$client = new Client ( $url );
		$client->setMethod ( $method );
		$client->setParameterPost ( $params );
		$return = $client->send ();
		return $return->getContent ();
	}
	
	
}
