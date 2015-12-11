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
			'su' => array (
				'type' => 'Segment',
				'options' => array (
					'route' => '/su[/:controller[/:action[/:id]]]',
					'constraints' => array (
						'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*' 
					),
					'defaults' => array (
						'__NAMESPACE__' => 'Su\Controller',
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
			'Su\Controller\Index' => 'su\Controller\IndexController',
			'Su\Controller\Article' => 'su\Controller\ArticleController'
		) 
	),
	'view_manager' => array (
		'template_path_stack' => array (
			'su' => __DIR__ . '/../view' 
		),
		'not_found_template' => 'error/404',
		'exception_template' => 'error/index',
		'template_map' => array (
			'su/index/index' => __DIR__ . '/../view/su/index/index.phtml' 
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
