<?php
/**
*	PlatformUserHasGroupPermission code.
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
*	PlatformUserHasGroupPermission code.
*
*	PHP version 5.3
*
*	@category Model
*	@package User
*	@author Rex chen <rexchen@synctech.ebiz.tw>
*	@copyright 2015 synctech.com
*/

class PlatformUserHasGroupPermission extends PermissionDbModel {
	
	/* 	Method of interface User. */	
	/**
	*	Get the entity table name.
	*
	*	@return string 
	*/
	public function getTable() {
		return "platform_user_has_group_permission";
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