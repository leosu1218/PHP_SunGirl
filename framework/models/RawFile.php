<?php
/**
*	RawFile code.
*
*	PHP version 5.3
*
*	@category Model
*	@package User
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

require_once( FRAMEWORK_PATH . 'system/models/PermissionDbModel.php' );

/**
*	RawFile code.
*
*	PHP version 5.3
*
*	@category Model
*	@package User
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

class RawFile extends PermissionDbModel {
	
	/* 	Method of interface User. */	
	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "raw_file";
	}

   	/**
	*	Check attributes is valid.
	*
	*	@param $attributes 	array Attributes want to checked.
	*	@return bool 		If valid return true.
	*/
	public function validAttributes($attributes) {
		
		if(array_key_exists("id", $attributes)) {
			throw new Exception("Can't write the attribute 'id'.");
		}

		if(array_key_exists("name", $attributes)) {
			throw new Exception("Can't write the attribute 'name'.");
		}

		if(array_key_exists("hash", $attributes)) {
			throw new Exception("Can't write the attribute 'hash'.");
		}

		if(array_key_exists("create_datetime", $attributes)) {
			throw new Exception("Can't write the attribute 'create_datetime'.");
		}

		if(array_key_exists("user_id", $attributes)) {
			throw new Exception("Can't write the attribute 'user_id'.");
		}

		return true;
	}

	/**
	*	Get Primary key attribute name
	*
	*	@return string
	*/
	public function getPrimaryAttribute() {
		return "id";
	}
}

?>