<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array (
	'router' => array (
		'routes' => array (
			'article' => array (
				'type' => 'Segment',
				'options' => array (
					'route' => '/article[/:controller[/:action[/:id]]]',
					'constraints' => array (
						'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*' 
					),
					'defaults' => array (
						'__NAMESPACE__' => 'Article\Controller',
						'controller' => 'index',
						'action' => 'index' 
					) 
				) 
			) 
		) 
	),
	'service_manager' => array (
		'abstract_factories' => array (
			'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
			'Zend\Log\LoggerAbstractServiceFactory' 
		),
		'aliases' => array (
			'translator' => 'MvcTranslator' 
		) 
	),
	'translator' => array (
		'locale' => 'en_US',
		'translation_file_patterns' => array (
			array (
				'type' => 'gettext',
				'base_dir' => __DIR__ . '/../language',
				'pattern' => '%s.mo' 
			) 
		) 
	),
	'controllers' => array (
		'invokables' => array (
			'Article\Controller\Index' => 'Article\Controller\IndexController',
			'Article\Controller\Flyjax' => 'Article\Controller\FlyjaxController' 
		) 
	),
	'view_manager' => array (
		'template_path_stack' => array (
			'article' => __DIR__ . '/../view' 
		),
		'not_found_template' => 'error/404',
		'exception_template' => 'error/index',
		'template_map' => array (
			'article/index/index' => __DIR__ . '/../view/article/index/index.phtml' 
		) 
	)
	,
	// Placeholder for console routes
	'console' => array (
		'router' => array (
			'routes' => array () 
		) 
	) 
);
