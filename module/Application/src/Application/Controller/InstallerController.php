<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 *
 * @author sandeepnarwal
 *        
 */
class InstallerController extends \Kernel\Engine\Controller  {
	const BASE_DB_ENTITY = "\Kernel\Db\Entity";
	const BASE_DB_FINDER = "\Kernel\Db\Finder";
	const BASE_DB_TABLE = "\Kernel\Db\Table";
	const GENERATED_PATH = "Kernel/Db/Generated";
	const GENERATED_NAMESPACE = "Kernel\Db\Generated";
	
	/*
	 * (non-PHPdoc)
	 * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
	 */
	public function indexAction() {
		$this->layout ( "layout/install" );
		$view = new ViewModel ( array (
			"generated_path" => self::GENERATED_PATH,
			"generated_namespace" => self::GENERATED_NAMESPACE 
		) );
		return $view;
	}
	
	/**
	 */
	private function _init() {
		$params = $this->params ()->fromPost ();
		
		$this->adapter = new \Zend\Db\Adapter\Adapter ( array (
			'driver' => 'mysqli',
			'host' => ($params ['host'] == "") ? "localhost" : $params ['host'],
			'database' => $params ['dbname'],
			'username' => $params ['username'],
			'password' => $params ['password'] 
		) );
		$this->params = $params;
	}
	
	/**
	 *
	 * @param unknown $schema        	
	 */
	private function dropDb() {
		$metadata = new \Zend\Db\Metadata\Metadata ( $this->adapter );
		
		// get the table names
		$tableNames = $metadata->getTableNames ();
		$this->adapter->query ( 'SET foreign_key_checks = 0' )->execute ();
		
		foreach ( $tableNames as $tableName ) {
			$sql = new \Zend\Db\Sql\Sql ( $this->adapter );
			$drop = new \Zend\Db\Sql\Ddl\DropTable ( $tableName );
			@$this->adapter->query ( $sql->getSqlStringForSqlObject ( $drop ), \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE );
		}
		
		$this->adapter->query ( 'SET foreign_key_checks = 1' )->execute ();
	}
	
	/**
	 */
	public function startAction() {
		$this->layout ( "layout/install" );
		$this->_init ();
		$this->dropDb ();
		
		$file = DOC_ROOT . "/config/schema/ddl.php";
		
		if (file_exists ( $file )) {
			
			$ddl = file_get_contents ( $file );
			$ddl_sequence = explode ( ";", $ddl );
			
			$error = false;
			foreach ( $ddl_sequence as $key => $value ) {
				if (trim ( $value ) != "")
					$this->adapter->query ( $value . ";" )->execute ();
			}
			
			$this->generateDbMapper ();
		} else {
			echo "<span class='text text-danger'>ERROR : DDL file does not exists. Make sure below file exists: <br><i> {$file}</i></span>";
			echo "<hr>";
			echo "<span class='text text-info'>Above file can have all your create table statements or any other sql queries.</span>";
		}
		
		die ();
	}
	
	/**
	 */
	public function generateDbMapper() {
		$metadata = new \Zend\Db\Metadata\Metadata ( $this->adapter );
		
		// get the table names
		$tableNames = $metadata->getTableNames ();
		echo "<pre>";
		foreach ( $tableNames as $table ) {
			echo '<br>Creating<strong> ' . $table . "</strong> ";
			$this->generateDirectoryPath ();
			$this->generateClassFile ( $table, $metadata->getTable ( $table )->getColumns () );
		}
		
		echo '<hr>---';
	}
	
	/**
	 */
	public function generateDirectoryPath() {
		@mkdir ( "module/" . $this->params ["directory_location"], 0777, true );
	}
	
	/**
	 *
	 * @param unknown $key        	
	 * @param unknown $value        	
	 */
	private function generateClassFile($table, $columns) {
		
		// convert table name like demo_user to DemoUser
		$value = str_replace ( " ", "", ucwords ( str_replace ( "_", " ", $table ) ) );
		$className = $value;
		$nameSpace = $this->params ['directory_namespace'];
		
		$this->generateEntityFile ( $className, $nameSpace, $value, $columns );
	}
	
	/**
	 *
	 * @param unknown $className        	
	 * @param unknown $nameSpace        	
	 * @param unknown $value        	
	 */
	private function generateEntityFile($className, $nameSpace, $value, $columns) {
		$this->generateEntityFileClass ( $className, $nameSpace, $value );
		$this->generateEntityFileClassTable ( $className, $nameSpace, $value, $columns );
		$this->generateEntityFileClassFinder ( $className, $nameSpace, $value );
	}
	
	/**
	 *
	 * @param unknown $className        	
	 * @param unknown $nameSpace        	
	 * @param unknown $value        	
	 */
	private function generateEntityFileClass($className, $nameSpace, $value) {
		
		// Passing configuration to the constructor:
		$class = new \Zend\Code\Generator\ClassGenerator ();
		$class->setName ( $className );
		$class->setNamespaceName ( $nameSpace );
		$class->setExtendedClass ( self::BASE_DB_ENTITY );
		$file = new \Zend\Code\Generator\FileGenerator ( array (
			'classes' => array (
				$class 
			) 
		) );
		
		$file->generate ();
		$classFile = DOC_ROOT . '/module/' . $this->params ["directory_location"] . "/" . $value . '.php';
		if (! file_exists ( $classFile )) {
			fopen ( $classFile, "w" );
			file_put_contents ( $classFile, $file->generate () );
		}
	}
	
	/**
	 *
	 * @param unknown $className        	
	 * @param unknown $nameSpace        	
	 * @param unknown $value        	
	 */
	private function generateEntityFileClassTable($className, $nameSpace, $value, $columns) {
		
		// Passing configuration to the constructor:
		$class = new \Zend\Code\Generator\ClassGenerator ();
		$class->setName ( $className . "Table" );
		$class->setNamespaceName ( $nameSpace );
		$class->setExtendedClass ( self::BASE_DB_TABLE );
		$class->addMethod ( '__construct', array (
			array (
				"name" => "tableGateway",
				"type" => "\Zend\Db\TableGateway\TableGateway" 
			) 
		), \Zend\Code\Generator\MethodGenerator::FLAG_PUBLIC, 'parent::__construct ( $tableGateway );' );
		
		$columnsArray = array ();
		foreach ( $columns as $column ) {
			$columnsArray [] = "'" . $column->getName () . "'";
		}
		$columns = implode ( ",", $columnsArray );
		$class->addMethod ( "getColumns", array (), \Zend\Code\Generator\MethodGenerator::FLAG_PUBLIC, " return array($columns);" );
		$file = new \Zend\Code\Generator\FileGenerator ( array (
			'classes' => array (
				$class 
			) 
		) );
		
		// Render the generated file
		$file->generate ();
		$classFile = DOC_ROOT . '/module/' . $this->params ["directory_location"] . "/" . $value . 'Table.php';
		fopen ( $classFile, "w" );
		file_put_contents ( $classFile, $file->generate () );
	}
	
	/**
	 *
	 * @param unknown $className        	
	 * @param unknown $nameSpace        	
	 * @param unknown $value        	
	 */
	private function generateEntityFileClassFinder($className, $nameSpace, $value) {
		// Passing configuration to the constructor:
		$class = new \Zend\Code\Generator\ClassGenerator ();
		$class->setName ( $className . "Finder" );
		$class->setNamespaceName ( $nameSpace );
		$class->setExtendedClass ( self::BASE_DB_FINDER );
		$class->addMethod ( '__construct', array (
			array (
				"name" => "table" 
			) 
		), \Zend\Code\Generator\MethodGenerator::FLAG_PUBLIC, 'parent::__construct ( $table );' );
		
		$file = new \Zend\Code\Generator\FileGenerator ( array (
			'classes' => array (
				$class 
			) 
		) );
		
		// Render the generated file
		$file->generate ();
		$classFile = DOC_ROOT . '/module/' . $this->params ["directory_location"] . "/" . $value . 'Finder.php';
		if (! file_exists ( $classFile )) {
			fopen ( $classFile, "w" );
			$returnFlag = file_put_contents ( $classFile, $file->generate () );
		}
		
		if ($returnFlag > 0) {
			echo "... <span class='glyphicon glyphicon-ok text text-success'></span>";
		} else {
			echo "... <span class='glyphicon glyphicon-remove text text-danger'></span> (not written)";
		}
	}
}
