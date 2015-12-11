<?php

namespace Model\Entity;

use Zend\Db\TableGateway\TableGateway;

/**
 *
 * @author Sandeepn
 *        
 */
class Schema {
	
	/**
	 *
	 * @var unknown
	 */
	static $prefix = "switch_";
	
	/**
	 * NOTE : only int is suported in associated as of now
	 * if not specified varchar(200) will be used
	 *
	 * @var unknown
	 */
	public static $schema = array (
		"user" => array (
			"entity" => "Model\Entity\Generated\User",
			"constants" => array (
				"ACTIVE" => "active",
				"INACTIVE" => "inactive",
				"USER" => "user",
				"SU" => "su" 
			),
			"columns" => array (
				array (
					"id",
					"data_type" => array (
						"int",
						"11",
						true 
					) 
				),
				array (
					"user_type" 
				),
				array (
					"first_name" 
				),
				array (
					"last_name" 
				),
				array (
					"username" 
				),
				array (
					"password" 
				),
				array (
					"mobile" 
				),
				array (
					"telephone" 
				),
				array (
					"dated" 
				),
				array (
					"status" 
				) 
			) 
		),
		"category" => array (
			"entity" => "Model\Entity\Generated\Category",
			"associate" => array (
				"user" => "user_id" 
			),
			"columns" => array (
				array (
					"id",
					"data_type" => array (
						"int",
						"11",
						true 
					) 
				),
				array (
					"user_id",
					"data_type" => array (
						"int",
						"11" 
					) 
				),
				array (
					"name" 
				),
				array (
					"dated" 
				) 
			) 
		),
		"article" => array (
			"entity" => "Model\Entity\Generated\Article",
			"associate" => array (
				"user" => "user_id" 
			),
			"columns" => array (
				array (
					"id",
					"data_type" => array (
						"int",
						"11",
						true 
					) 
				),
				array (
					"user_id",
					"data_type" => array (
						"int",
						"11" 
					) 
				),
				array (
					"title" 
				),
				array (
					"blob" 
				),
				array (
					"dated" 
				),
				array (
					"status" 
				) 
			) 
		),
		"category_article" => array (
			"entity" => "Model\Entity\Generated\CategoryArticle",
			"associate" => array (
				"category" => "category_id",
				"article" => "article_id" 
			),
			"columns" => array (
				array (
					"id",
					"data_type" => array (
						"int",
						"11",
						true 
					) 
				),
				array (
					"category_id",
					"data_type" => array (
						"int",
						"11" 
					) 
				),
				array (
					"article_id",
					"data_type" => array (
						"int",
						"11" 
					) 
				) 
			) 
		) 
	);
}
