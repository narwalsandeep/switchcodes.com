<?php

namespace Component;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface {
	
	/**
	 *
	 * @param MvcEvent $e        	
	 */
	public function onBootstrap(MvcEvent $e) {
		$eventManager = $e->getApplication ()->getEventManager ();
		$moduleRouteListener = new ModuleRouteListener ();
		$moduleRouteListener->attach ( $eventManager );
	}
	
	/**
	 *
	 * @return multitype:multitype:string multitype:multitype:string
	 */
	public function getAutoloaderConfig() {
		return array (
			'Zend\Loader\StandardAutoloader' => array (
				'namespaces' => array (
					__NAMESPACE__ => __DIR__ 
				) 
			) 
		);
	}
}
