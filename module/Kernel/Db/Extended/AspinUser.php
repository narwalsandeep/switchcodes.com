<?php

namespace Kernel\Db\Extended;

use Zend\Db\Sql\Select;

class AspinUser {
	
	/**
	 *
	 * @param unknown $table        	
	 */
	public function __construct($select, $gateway) {
		$this->select = $select;
		$this->gateway = $gateway;
	}
	
	/**
	 *
	 * @param unknown $func        	
	 */
	public function __call($func, $args) {
		die ( "$func is not implemented in " . __FILE__ );
	}
	
	/**
	 * s * @param unknown $params
	 */
	public function doLogin($params) {
		if (self::_isValid ( $params )) {
			$sql = "
			
			SELECT
				id
			FROM
				aspin_schema_locale
			WHERE
				locale_id='1' and
				schema_id in (
					SELECT 
						id 
					FROM
						aspin_schema
					WHERE
					column_code in (always send column_code here..., ) and
					business_id='1' and
						entity_id=(
							SELECT 
								id
							FROM
								aspin_entity 
							WHERE
								name='" . $params ['entity_id'] . "'
						)
				)
		
			";
			
			$resultSet = $this->_getResult ( $sql );
			foreach ( $resultSet as $key => $value ) {
				$sql = "
					SELECT 
						sv.id,sv.record_id,sv.value,sl.value as column_name
					FROM 
						aspin_schema_value sv, aspin_schema_locale sl
					WHERE
						sv.schema_locale_id=sl.id and
						schema_locale_id='" . $value->id . "' and
						value='" . $params [$value->id] . "'
				";
				$resultSet = $this->_getResult($sql);
				foreach($resultSet as $key=>$value){
					$temp[$value->record_id][$value->column_name] = $value->value;
				}
				
			}
		}
	}
	
	/**
	 *
	 * @param unknown $sql        	
	 * @return \Zend\Db\ResultSet\ResultSet|multitype:
	 */
	private function _getResult($sql) {
		$stmt = $this->gateway->getAdapter ()->createStatement ( $sql );
		$stmt->prepare ();
		$result = $stmt->execute ();
		if ($result->isQueryResult ()) {
			$resultSet = new \Zend\Db\ResultSet\ResultSet ();
			$resultSet->initialize ( $result );
			return $resultSet;
		}
		return array ();
	}
	
	/**
	 *
	 * @param unknown $params        	
	 * @return boolean
	 */
	private static function _isValid($params) {
		return true;
	}
}

